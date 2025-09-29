const LOCATION_IQ_API_KEY = "pk.3f4855e07c49c984f1c16d65b8881f38";

/**
 * Cari lokasi berdasarkan data Wi-Fi menggunakan LocationIQ
 * @param {Object} data - Data Wi-Fi Access Points
 * @returns {Promise<{lat: string, lon: string} | null>} - Objek koordinat atau null jika gagal
 */
export async function findLocation(data) {
    const endpoint = `https://us1.locationiq.com/v1/search/wlan?key=${LOCATION_IQ_API_KEY}`;

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`Permintaan gagal dengan status ${response.status}`);
        }

        const result = await response.json();

        if (Array.isArray(result) && result.length > 0) {
            const { lat, lon } = result[0];
            return { lat, lon };
        } else {
            console.warn("Lokasi tidak ditemukan dalam respons");
            return null;
        }

    } catch (error) {
        console.error("Gagal mendapatkan lokasi:", error.message);
        return null;
    }
}

export async function showLocation(data) {
    const loc = await findLocation(data);
    if (loc) {
        return `${loc.lat},${loc.lon}`;
    } else {
        return "";
    }
}