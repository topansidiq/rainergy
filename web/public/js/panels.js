let audioContext;
let soundEnabled = false;

document.addEventListener("DOMContentLoaded", () => {
    const startButton = document.getElementById('start-sound');
    if (startButton) {
        startButton.addEventListener('click', () => {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            audioContext.resume();
            soundEnabled = true;
            console.log("AudioContext enabled!");
        });
    }
});

function playNotification() {
    if (!soundEnabled || !audioContext) {
        console.warn("AudioContext not allowed — waiting for user gesture!");
        return;
    }
    const osc = audioContext.createOscillator();
    const gain = audioContext.createGain();
    osc.type = "square";
    osc.frequency.value = 2500;
    gain.gain.value = 0.2;
    osc.connect(gain);
    gain.connect(audioContext.destination);
    osc.start();
    osc.stop(audioContext.currentTime + 0.8);
}

function pageReload() {
    window.location.reload();
}

async function getPanelReading(url) {
    try {
        const res = await fetch(url);
        const json = await res.json();
        const now = new Date();
        const data = json.data;

        console.log(`%c[${now.toLocaleString()}] Fetch panel reading for panel ${json.panel_id}`, 'color: green');
        console.info(data);
        console.info(`%c${json.message}`, 'color: skyblue;');

        return data;
    } catch (error) {
        console.error(error);
    }
}
