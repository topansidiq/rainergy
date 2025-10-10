<h1 align="center" style="color:#2c3e50;">Rainergy Monitoring System</h1>

<p align="center" style="color:#7f8c8d;">
A real-time solar panel monitoring application built for <strong>Rainergy</strong> — an initiative to create carbon-free energy systems in collaboration with <strong>United Tractors (Astra Group)</strong>.
</p>

---

## Overview

**Rainergy Monitoring System** is a web-based platform designed to monitor solar panel performance and power generation in real-time.  
The system ensures reliable, emission-free energy tracking with integrated automation and alerting through Telegram.

---

## Tech Stack

| Layer | Technology |
|--------|-------------|
| **Frontend / Web App** | Laravel 12 |
| **API Service** | Node.js + Hono + Knex |
| **Database** | MySQL 8 |
| **Real-time Monitoring** | WebSocket / Event-driven architecture |
| **Bot Integration** | Telegram Bot API |

---

## Features

- **Real-Time Dashboard:** Live monitoring of voltage, current, and power generation.  
- **Alert System:** Telegram bot integration for instant notifications.  
- **Data Visualization:** Interactive charts and analytics.  
- **Device Management:** Add, configure, and monitor solar units remotely.  
- **API Access:** RESTful and lightweight endpoints for data exchange.  

---

## Architecture Overview

```mermaid
graph TD
  A[ESP32 / Sensor Node] -->|Send Data| B[Node.js API (Hono + Knex)]
  B -->|Store Data| C[(MySQL Database)]
  B -->|Push Updates| D[Laravel Web Dashboard]
  D -->|Display Real-time Charts| E[User Interface]
  B -->|Send Alerts| F[Telegram Bot]
```

## Installation
```green
git clone https://github.com/yourusername/rainergy.git
cd rainergy
```
