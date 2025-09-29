#include <WiFi.h>
#include <SPI.h>
#include <LoRa.h>
#include <ArduinoJson.h>
#include <WebServer.h>
#include <HTTPClient.h>

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
int statusPompa, statusWiper;
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
    Serial.println("LoRa Receiver is ready!");

    // Install unit
    unitId = "u-" + String((uint32_t)ESP.getEfuseMac(), HEX);
    location = geolocateViaWifi();

    if (isUnitExist(unitId))
    {
        Serial.println("Unit " + unitId + " ready!");
    }
    else
    {
        installUnit(unitId, 1, location);
        Serial.print("New unit detected: " + unitId + ". Location: " + location);
    }

    delay(3000);
}

void loop()
{
    // Start LED indicator is success connect to WiFi
    digitalWrite(ledPin2, HIGH);

    // Starting manipulation LoRa package
    int packetSize = LoRa.parsePacket();
    if (packetSize)
    {
        String payload = "";
        while (LoRa.available())
        {
            payload += (char)LoRa.read();
        }
        Serial.println("New data: " + payload);

        StaticJsonDocument<256> doc;
        DeserializationError error = deserializeJson(doc, payload);
        if (error)
        {
            Serial.print("Parsing failed: ");
            Serial.println(error.c_str());
            return;
        }

        panelId = doc["panel_id"].as<String>();
        dustDensity = doc["dust"];
        voltagePanel = doc["voltage"];
        currentPanel = doc["current"];
        statusPompa = doc["pump_status"];
        statusWiper = doc["wiper_status"];

        if (WiFi.status() == WL_CONNECTED)
        {
            HTTPClient http;

            if (isPanelExist(panelId))
            {
                http.begin(serverName + "/panels/" + panelId);
                http.addHeader("Content-Type", "application/json");

                // bikin JSON khusus body
                DynamicJsonDocument bodyDoc(512);
                bodyDoc["dust"] = dustDensity;
                bodyDoc["current"] = currentPanel;
                bodyDoc["voltage"] = voltagePanel;
                bodyDoc["pump_status"] = statusPompa;
                bodyDoc["wiper_status"] = statusWiper;

                String requestBody;
                serializeJson(bodyDoc, requestBody);

                Serial.println("Sending JSON");
                Serial.println(requestBody);

                int httpResponseCode = http.PUT(requestBody);
                Serial.print("Response: ");
                Serial.println(httpResponseCode);
                http.end();
            }
            else
            {
                installPanel(panelId, unitId);
            }
        }
        else
        {
            Serial.println("WiFi disconnected!");
            wifi_status = false;
            digitalWrite(ledPin2, LOW);
        }

        delay(5000);
    }
}

void installUnit(String unit_id, int user_id, String location)
{
    HTTPClient http;
    http.begin(serverName + "/units");
    http.addHeader("Content-Type", "application/json");

    // Buat JSON object
    StaticJsonDocument<256> doc;
    doc["unit_id"] = unit_id;
    doc["user_id"] = user_id;
    doc["location"] = location;

    String jsonData;
    serializeJson(doc, jsonData);

    Serial.println("Sending JSON:");
    Serial.println(jsonData);

    int httpResponseCode = http.POST(jsonData);

    if (httpResponseCode > 0)
    {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String response = http.getString();
        Serial.println("Response: " + response);
    }
    else
    {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
    }
    http.end();
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

void installPanel(String panel_id, String unit_id)
{
    HTTPClient http;
    http.begin(serverName + "/panels");
    http.addHeader("Content-Type", "application/json");

    // Buat JSON object
    StaticJsonDocument<256> doc;
    doc["panel_id"] = panel_id;
    doc["unit_id"] = unit_id;

    String jsonData;
    serializeJson(doc, jsonData);

    Serial.println("Sending JSON:");
    Serial.println(jsonData);

    int httpResponseCode = http.POST(jsonData);

    if (httpResponseCode > 0)
    {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String response = http.getString();
        Serial.println("Response: " + response);
    }
    else
    {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
    }

    http.end();
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