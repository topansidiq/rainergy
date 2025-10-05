#include <WiFi.h>
#include <SPI.h>
#include <LoRa.h>
#include <ArduinoJson.h>
#include <WebServer.h>
#include <HTTPClient.h>
#include <vector>

std::vector<String> registeredPanels;

/**
 * Bussiness logic
 * 1. Receiver on
 * 2. LCD/serial monitor display uid esp receiver
 * 2. Receiver enter to looping waiting for user add new unit
 * 2. User login
 * 2. Add new unit with uid esp receiver
 * 3. If success and receiver detected that unit has been installed
 * 3. Receiver ready
 * 4. Transmitter on
 * 5. Transmitter send uid esp transmitter to receiver
 * 6. LCD at reveiver display uid esp transmitter
 */

// WiFi and server configuration
const char *ssid = "sa";
const char *password = "00009999";
String serverName = "http://10.158.196.183:3000";
bool wifi_status = false;

// LoRa configuration
#define LORA_SCK 5
#define LORA_MISO 19
#define LORA_MOSI 23
#define LORA_CS 18
#define LORA_RST 14
#define LORA_IRQ 26
#define ledPin2 2

// Variable initialization
float dustDensity, voltagePanel, currentPanel;
int rainStatus, wiperStatus;
String panelId, unitId, location;

String geolocateViaWifi()
{
    // scan Wi-Fi
    int n = WiFi.scanNetworks(false, true); // show_hidden=false, read_bssid=true
    if (n <= 0)
    {
        Serial.println("No networks found");
        return "{}"; // kalau tidak ada WiFi, balikin JSON kosong
    }

    // build JSON
    DynamicJsonDocument doc(2048);
    JsonArray arr = doc.createNestedArray("wifiAccessPoints");

    for (int i = 0; i < n && i < 10; ++i)
    { // kirim maksimal 10 AP
        JsonObject ap = arr.createNestedObject();
        ap["macAddress"] = WiFi.BSSIDstr(i); // BSSID e.g. "01:23:..."
        ap["signalStrength"] = WiFi.RSSI(i); // RSSI e.g. -67
    }

    String body;
    serializeJson(doc, body);

    return body; // sekarang fungsi mengembalikan JSON string
}

void setup()
{
    // Serial Monitor
    Serial.begin(115200);

    // Setup WiFi indicator
    pinMode(ledPin2, OUTPUT);
    digitalWrite(ledPin2, LOW);

    // Start connect to WiFi
    WiFi.begin(ssid, password);
    Serial.print("Try connect to WiFi!");
    delay(2000);

    if (WiFi.status() == WL_CONNECTED)
    {
        // Success connect
        Serial.println("\nWiFi connected!");
        Serial.print("IP Address: ");
        Serial.println(WiFi.localIP());
    }
    else
    {
        Serial.print("Failed connect to WiFi!");
    }

    SPI.begin(LORA_SCK, LORA_MISO, LORA_MOSI, LORA_CS);
    LoRa.setPins(LORA_CS, LORA_RST, LORA_IRQ);
    if (!LoRa.begin(433E6))
    {
        Serial.println("Failed starting LoRa!");
        return;
    }
    Serial.println("LoRa is ready!");

    // Install unit
    unitId = "u-" + String((uint32_t)ESP.getEfuseMac(), HEX);
    location = geolocateViaWifi();

    Serial.println("Checking for unit: " + unitId);

    bool notUnit = true;
    int dotCount = 0;

    while (notUnit)
    {
        if (isUnitExist(unitId))
        {
            notUnit = false;
            break;
        }

        Serial.println("Unit doesn't exist. Please install this unit on your account with ID: " + unitId);
        delay(2000);

        // Tampilkan animasi titik-titik "Waiting for unit installation..."
        Serial.print("Waiting for unit installation");
        for (int i = 0; i < dotCount; i++)
        {
            Serial.print(".");
        }
        Serial.println(); // pindah baris
        delay(1000);

        dotCount++;
        if (dotCount > 5)
            dotCount = 1; // reset titik biar gak kepanjangan

        if (isUnitExist(unitId))
        {
            notUnit = false;
        }
    }

    Serial.println("Unit " + unitId + " ready!");
}

// Simpan daftar panel yang sudah diketahui terdaftar

void loop()
{
    digitalWrite(ledPin2, HIGH); // LED: WiFi tersambung

    int packetSize = LoRa.parsePacket();
    if (packetSize)
    {
        // Ambil data payload
        String payload = "";
        while (LoRa.available())
        {
            payload += (char)LoRa.read();
        }

        Serial.println("New LoRa data: " + payload);

        // Parsing JSON
        StaticJsonDocument<256> doc;
        DeserializationError error = deserializeJson(doc, payload);
        if (error)
        {
            Serial.print("Parsing failed: ");
            Serial.println(error.c_str());
            return;
        }

        // Ambil data dari JSON
        String panelId = doc["panel_id"].as<String>();
        float voltage = doc["voltage"];
        float current = doc["current"];
        bool rainStatus = doc["rain_status"];
        bool wiperStatus = doc["wiper_status"];
        int lastClean = doc["last_clean"];

        if (WiFi.status() == WL_CONNECTED)
        {
            HTTPClient http;

            // Cek apakah panel sudah ada di daftar terdaftar
            bool knownPanel = false;
            for (String id : registeredPanels)
            {
                if (id == panelId)
                {
                    knownPanel = true;
                    break;
                }
            }

            // Kalau belum pernah didaftarkan, cek ke server
            if (!knownPanel)
            {
                Serial.println("Checking panel: " + panelId);

                if (isPanelExist(panelId))
                {
                    Serial.println("Panel " + panelId + " found in database!");
                    registeredPanels.push_back(panelId);
                }
                else
                {
                    // Panel belum ada → tampilkan pesan tunggu
                    int dotCount = 0;

                    while (!isPanelExist(panelId))
                    {
                        Serial.print("\rWaiting for panel installation"); // tetap di baris yang sama

                        for (int i = 0; i < dotCount; i++)
                        {
                            Serial.print(".");
                        }

                        // Tambahkan spasi untuk "menghapus" sisa teks lama
                        Serial.print("       ");

                        dotCount++;
                        if (dotCount > 5)
                            dotCount = 1;

                        delay(1000);
                    }

                    Serial.println("\nPanel " + panelId + " registered!");
                    registeredPanels.push_back(panelId);
                }
            }

            // Kirim data terbaru ke server
            DynamicJsonDocument bodyDoc(512);
            bodyDoc["current"] = current;
            bodyDoc["voltage"] = voltage;
            bodyDoc["rain_status"] = rainStatus;
            bodyDoc["wiper_status"] = wiperStatus;
            bodyDoc["last_clean"] = lastClean;

            String requestBody;
            serializeJson(bodyDoc, requestBody);

            http.begin(serverName + "/panels/" + panelId);
            http.addHeader("Content-Type", "application/json");

            Serial.println("\nSending update for panel " + panelId);
            Serial.println(requestBody);

            int httpResponseCode = http.PUT(requestBody);
            Serial.print("Response: ");
            Serial.println(httpResponseCode);

            http.end();
        }
        else
        {
            Serial.println("WiFi disconnected!");
            wifi_status = false;
            digitalWrite(ledPin2, LOW);
        }
    }

    // Biarkan loop terus berjalan tanpa blocking
    delay(100);
}

bool isUnitExist(String id)
{
    HTTPClient http;
    http.begin(serverName + "/units/" + id);
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0)
    {
        if (httpResponseCode == 200)
        {
            String payload = http.getString(); // ambil body JSON

            // Buat buffer JSON (ubah ukuran sesuai kebutuhan JSON)
            StaticJsonDocument<256> doc;

            DeserializationError error = deserializeJson(doc, payload);
            if (!error)
            {
                const char *status = doc["status"];
                if (strcmp(status, "success") == 0)
                {
                    http.end();
                    return true; // unit memang ada
                }
            }
        }
    }

    http.end();
    return false;
}

bool isPanelExist(String id)
{
    HTTPClient http;
    http.begin(serverName + "/panels/" + id);
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0)
    {
        if (httpResponseCode == 200)
        {
            String payload = http.getString(); // ambil body JSON

            // Buat buffer JSON (ubah ukuran sesuai kebutuhan JSON)
            DynamicJsonDocument doc(1024);

            DeserializationError error = deserializeJson(doc, payload);
            if (!error)
            {
                const char *status = doc["status"];
                if (strcmp(status, "success") == 0)
                {
                    http.end();
                    return true; // unit memang ada
                }
            }
        }
    }

    http.end();
    return false;
}