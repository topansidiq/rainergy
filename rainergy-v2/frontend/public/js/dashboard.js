function dashboard() {
  return {
    data: [],
    latest: {},
    async loadData() {
      try {
        const res = await fetch("api/monitor/history?limit=20");
        this.data = await res.json();
        this.latest = this.data[0] || {};
      } catch (error) {
        console.error("Failed to load data:", error);
      }
    },
  };
}
