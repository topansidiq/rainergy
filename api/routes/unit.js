import { Hono } from 'hono';
import { db } from '../server/db.js';
import { findLocation, getLocation, showLocation } from '../helpers/location.js';

export const unitRoutes = new Hono();

unitRoutes.post('/', async (c) => {
    try {
        const body = await c.req.json();
        const { unit_id, user_id, location } = body;

        // Insert unit baru
        const [id] = await db('units').insert({
            unit_id,
            user_id,
            location
        });

        const unit = await db('units').where({ id }).first();

        return c.json({ status: "success", message: "Unit created!", data: unit });
    } catch (error) {
        console.error("Failed to insert unit", error);
        return c.json({ status: "error", message: "Failed to create unit" }, 500);
    }
});

unitRoutes.get('/:unit_id', async (c) => {
    let message;
    try {
        const { unit_id } = c.req.param();
        const unit = await db('units').where({ unit_id }).first();
        if (!unit) {
            message = `No unit was found!`;
            console.log(message);
            return c.json({ status: "empty", message: message });
        }
        message = `Successfully retrieved unit data with ID ${unit_id}`;
        console.log(message);
        return c.json({ status: "success", message: message, data: unit });
    } catch (e) {
        message = "Failed to get unit!";
        console.error(`${message}\n`, error);
        return c.json({ status: "error", message: message }, 500);
    }
})