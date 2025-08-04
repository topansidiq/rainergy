function monitoring() {
  console.log("load");
  return {
    metrics: [],
    lastUpdate: null,
    async loadData() {
      try {
        const res = await fetch("/api/data");
        const data = await res.json();
        this.lastUpdate = data.timestamp * 1000;

        this.metrics = [
          { label: "Voltage", value: data.voltage, unit: "V" },
          { label: "Current", value: data.current, unit: "A" },
          { label: "Power", value: data.power, unit: "W" },
          { label: "Energy", value: data.energy, unit: "Wh" },
          { label: "Intensity", value: data.intensity, unit: "lx" },
          { label: "Temperature", value: data.temperature, unit: "°C" },
        ];
      } catch (e) {
        console.error("Failed to fetch monitoring data", e);
      }

      setTimeout(() => this.loadData(), 5000);
    },
    formatTime(ts) {
      return new Date(ts).toLocaleString();
    },
  };
}
