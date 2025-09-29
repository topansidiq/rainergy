import { Hono } from 'hono';
import { db } from '../server/db.js';

export const panelRoutes = new Hono();

// Get all panel data
panelRoutes.get("/", async (c) => {
    let message;
    try {
        const sort = c.req.query("sort");
        const order = c.req.query("order");

        // List of columns that can be sorted to be safe from SQL injection
        const allowedSortFields = [
            "panel_id",
            "unit_id",
            "dust",
            "current",
            "voltage",
            "power",
            "pump_status",
            "wiper_status",
            "installed_at"
        ];

        // Default sorting
        let sortField = "installed_at";
        let sortOrder = "desc";

        if (sort && allowedSortFields.includes(sort)) {
            sortField = sort;
        }

        if (order && ["asc", "desc"].includes(order.toLowerCase())) {
            sortOrder = order.toLowerCase();
        }

        const panels = await db("panels")
            .select("*")
            .orderBy(sortField, sortOrder);

        if (panels.length === 0) {
            message = "No panel data was found";
            console.log(message);
            return c.json({ status: "empty", message });
        }

        message = `Successfully retrieved ${panels.length} panel data`;
        console.log(message);
        return c.json({ status: "success", message: message, data: panels });
    } catch (error) {
        message = "Failed to retrieve panel data";
        console.error(`${message}\n`, error);
        return c.json({ status: "error", message: message }, 500);
    }
});

// Get 20 latest panel data
panelRoutes.get('/latest', async (c) => {
    let message;
    try {
        const sort = c.req.query("sort");
        const order = c.req.query("order");
        const allowedSortFields = ["panel_id", "unit_id", "dust", "current", "voltage", "power", "pump_status", "wiper_status", "installed_at"];
        let sortField = "installed_at";
        let sortOrder = "desc";
        if (sort && allowedSortFields.includes(sort)) sortField = sort;
        if (order && ["asc", "desc"].includes(order.toLowerCase())) sortOrder = order.toLowerCase();
        const panels = await db("panels").select("*").orderBy(sortField, sortOrder).limit(20);
        if (panels.length === 0) {
            message = "No panel data was found";
            console.log(message);
            return c.json({ status: "empty", message });
        }
        message = `Successfully retrieved ${panels.length} panel data (sorted by ${sortField} ${sortOrder})`;
        console.log(message);
        return c.json({ status: "success", message: message, data: panels });
    } catch (error) {
        message = "Failed to retrieve panel data";
        console.error(`${message}\n`, error);
        return c.json({ status: "error", message: message }, 500);
    }
})

// Insert new panel
panelRoutes.post('/', async (c) => {
    try {
        const body = await c.req.json();
        const { panel_id, unit_id } = body;

        const [id] = await db('panels').insert({
            panel_id,
            unit_id
        });

        const panel = await db('panels').where({ id }).first();
        if (panel) {
            console.log(`Successfully install new panel with id ${panel_id}.`)
        }

        // Insert new panel to table recording panel data
        const recorded_at = db.fn.now();
        const data = new Date();
        const data_id = "d" + String(data.getHours()).padStart(2, '0') + String(data.getMinutes()).padStart(2, '0');
        const panel_record = await db('panel_readings').insert({
            panel_id,
            data_id,
        });
        if (panel_record) {
            console.log(`Successfully first record new panel data with data id ${data_id}.`)
        }

        return c.json({ status: "success", message: "Panel created!", data: panel });
    } catch (error) {
        console.error("Failed to insert panel", error);
        return c.json({ status: "error", message: "Failed to create panel" }, 500);
    }
});


// Get a panel by Id
panelRoutes.get('/:panel_id', async (c) => {
    let message;
    try {
        const { panel_id } = c.req.param();
        const panel = await db('panels').where({ panel_id }).first();
        if (!panel) {
            message = `No panel data was found`;
            console.log(message);
            return c.json({ status: "empty", message: message });
        }
        message = `Successfully retrieved panel data with ID ${panel_id}`;
        console.log(message);
        return c.json({ status: "success", message: message, data: panel });
    } catch (e) {
        message = "Failed to get panel!";
        console.error(`${message}\n`, error);
        return c.json({ status: "error", message: message }, 500);
    }
});

// Update panel sensor
panelRoutes.put('/:panel_id', async (c) => {
    try {
        const { panel_id } = c.req.param();
        const body = await c.req.json();
        const { dust, current, voltage, pump_status, wiper_status } = body;

        const power = current * voltage;

        const updated = await db('panels')
            .where({ panel_id })
            .update({
                dust,
                current,
                voltage,
                pump_status,
                wiper_status,
                power,
                updated_at: db.fn.now()
            });

        if (!updated) {
            return c.json({ status: "empty", message: "No panel updated" });
        }

        const panel = await db('panels').where({ panel_id }).first();
        const record_panel = await db('panel_readings').where({ panel_id }).first();
        const recorded_at = db.fn.now();
        const data = new Date();
        const data_id = "d" + String(data.getHours()).padStart(2, '0') + String(data.getMinutes()).padStart(2, '0');
        const created_at = record_panel.created_at;

        const recoding_data = await db('panel_readings').insert({
            panel_id, data_id, dust, current, voltage, power, recorded_at, created_at
        });

        if (recoding_data) {
            console.log(`Successfully record new data for panel ${panel_id} with data_id ${data_id}`);
        }

        return c.json({ status: "success", message: "Panel updated!", data: panel });
    } catch (error) {
        console.error("Failed to update panel", error);
        return c.json({ status: "error", message: "Failed to update panel" }, 500);
    }
});


// Toggle wiper status
panelRoutes.put('/wiper/:panel_id', async (c) => {
    let message;
    try {
        const { panel_id } = c.req.param();
        let panel = await db('panels').where({ panel_id }).first();
        if (!panel) {
            message = "No panel was found";
            return c.json({ status: "empty", message: message });
        }
        const wiper_status = panel.wiper_status === 0 ? 1 : 0;
        await db('panels').where({ panel_id }).update({ wiper_status, updated_at: db.fn.now() });
        panel = await db('panels').where({ panel_id }).first();
        message = `Wiper status of ${panel_id} updated to ${wiper_status}`;
        return c.json({ status: 'success', message: message, data: panel });
    } catch (error) {
        message = "Failed to update wiper status!"
        console.error(`${message}\n`, error);
        return c.json({ status: "error", message: message }, 500);
    }
});

// Update dust data sensor
panelRoutes.put('/dust/:panel_id', async (c) => {
    let message;
    try {
        const { panel_id } = c.req.param();
        const body = await c.req.json();
        const { dust } = body;
        const panel = await db('panels').where({ panel_id }).select("dust").first();
        const updated = await db('panels')
            .where({ panel_id })
            .update({
                dust, updated_at: db.fn.now()
            });
        if (updated === 0) {
            return c.json({ status: "error", message: "Panel not found!" }, 404);
        }
        const dustDisplay = dust > panel.dust ? `Dust level increased from ${panel.dust} to ${dust}` : `Dust level decreased from ${panel.dust} to ${dust}`;
        const message = `Data ${panel_id} was updated\n${dustDisplay}`;
        console.log(message);
        return c.json({ status: "success", message: dustDisplay, data: { panel_id, dust } });
    } catch (error) {
        message = "Failed to update dust data panel!";
        console.error(`${message}\n`, error);
        return c.json({ status: "error", message: message }, 500);
    }
});