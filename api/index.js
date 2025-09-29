import { Hono } from 'hono';
import { cors } from 'hono/cors'
import { serve } from '@hono/node-server';
import { panelRoutes } from './routes/panel.js';
import { unitRoutes } from './routes/unit.js';

const app = new Hono();

app.use("*", cors());

app.get('/', (c) => {
    c.text('Rainergy Monitoring API');
})

app.route('/panels', panelRoutes);
app.route('/units', unitRoutes);

serve(app, (info) => {
    console.log(`Server running at http://localhost:${info.port}`);
})