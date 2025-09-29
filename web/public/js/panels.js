async function getPanelRecord(api) {
    try {
        const res = await fetch(api);
        const resJson = await res.json();
        const status = resJson.status;
        const message = resJson.message;
        const panel = resJson.data;

        console.log('Status\t:', status);
        console.log('Message\t:', message);
        console.log('Data\t:', panel);
        return panel;
    } catch (err) {
        console.error("Error fetching panel record:", err);
        return null;
    }
}

async function renderPanelChartD3(container, api, dataset = "dust") {
    try {
        const res = await fetch(api);
        const json = await res.json();
        const records = json.data;

        // ambil labels & data
        const labels = records.map(r => r.data_id);
        const data = records.map(r => r[dataset]);

        // clear container biar nggak numpuk
        d3.select(container).selectAll("*").remove();

        const margin = { top: 20, right: 30, bottom: 40, left: 60 };
        const width = 600 - margin.left - margin.right;
        const height = 300 - margin.top - margin.bottom;

        const svg = d3.select(container)
            .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        // skala
        const x = d3.scalePoint()
            .domain(labels)
            .range([0, width]);

        const y = d3.scaleLinear()
            .domain([0, d3.max(data)])
            .nice()
            .range([height, 0]);

        // garis
        const line = d3.line()
            .x((d, i) => x(labels[i]))
            .y(d => y(d))
            .curve(d3.curveMonotoneX);

        // axis
        svg.append("g")
            .attr("transform", `translate(0,${height})`)
            .call(d3.axisBottom(x).ticks(labels.length));

        svg.append("g")
            .call(d3.axisLeft(y));

        // path
        svg.append("path")
            .datum(data)
            .attr("fill", "none")
            .attr("stroke", dataset === "dust" ? "red" : dataset === "current" ? "blue" : "green")
            .attr("stroke-width", 2)
            .attr("d", line);

        // titik data
        svg.selectAll("circle")
            .data(data)
            .enter()
            .append("circle")
            .attr("cx", (d, i) => x(labels[i]))
            .attr("cy", d => y(d))
            .attr("r", 4)
            .attr("fill", dataset === "dust" ? "red" : dataset === "current" ? "blue" : "green");

    } catch (err) {
        console.error("Error rendering D3 chart:", err);
    }
}
