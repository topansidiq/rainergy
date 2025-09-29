import { Hono } from 'hono';
import { db } from '../server/db.js';

export const sessionRoute = new Hono();

sessionRoute.post('/', async (c) => {
    let message;
    try {
        const body = await c.req.json();
        const { panel_id, unit_id, dust, current, voltage, pump_status, wiper_status } = body;
        const power = current * voltage;
        await db("panels").insert({ panel_id, unit_id, dust, current, voltage, power, pump_status, wiper_status });
        panel = await db('panels').where({ panel_id }).first();
    } catch (error) {

    }
})