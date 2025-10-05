import { Hono } from 'hono';
import { db } from '../server/db.js';
import { getRandomValue } from '../helpers/random.js';

export const panelRoutes = new Hono();

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
        let { current, voltage, rain_status, wiper_status, last_clean } = body;
        const power = current * voltage;
        const now = new Date();
        const last_cleaning = new Date(now.getTime() - last_clean);

        if (voltage <= 7) {
            current = getRandomValue()
        } else {
            current = getRandomValue(0.9, 1.1);
        }

        let updateData = {
            current,
            voltage,
            power,
            rain_status,
            wiper_status,
            last_cleaning,
            updated_at: db.fn.now(),
        };

        const updated = await db('panels')
            .where({ panel_id })
            .update(updateData);

        if (!updated) {
            return c.json({ status: "empty", message: "No panel updated" });
        }

        const full_record_data_daily = await db('panel_readings')
            .where('panel_id', panel_id)
            .whereRaw('DATE(recorded_at) = CURDATE()')
            .orderBy('recorded_at', 'asc')
            .limit(24);

        if (full_record_data_daily.length >= 24) {
            console.log("Daily data record is already full.");
            return c.json({ status: "full", message: "Daily data already complete" });
        } else {
            const d = new Date();
            const data_id = "d-" + d.toLocaleString("en-GB", {
                year: "numeric", month: "2-digit", day: "2-digit",
                hour: "2-digit", minute: "2-digit", second: "2-digit"
            }).replace(/[^\d]/g, "");

            await db('panel_readings').insert({
                panel_id,
                data_id,
                current,
                voltage,
                power,
                recorded_at: db.fn.now(),
            });

            console.log(`Recorded new data for ${panel_id} (${data_id})`);
        }

        const panel = await db('panels').where({ panel_id }).first();

        return c.json({ status: "success", message: "Panel updated!", data: panel });

    } catch (error) {
        console.error("Failed to update panel:", error);
        return c.json({ status: "error", message: "Failed to update panel" }, 500);
    }
});