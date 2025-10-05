import { Hono } from 'hono';
import { db } from '../server/db.js';

export const unitRoutes = new Hono();

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