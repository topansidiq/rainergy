<h1 align="center" style="color:#2c3e50;">Rainergy Monitoring System</h1>

<p align="center" style="color:#7f8c8d;">
A real-time solar panel monitoring application built for <strong>Rainergy</strong> — an initiative to create carbon-free energy systems in collaboration with <strong>United Tractors (Astra Group)</strong>.
</p>

---

## 🌓 Overview

**Rainergy Monitoring System** is a web-based platform designed to monitor solar panel performance and power generation in real-time.  
The system ensures reliable, emission-free energy tracking with integrated automation and alerting through Telegram.

---

## ⚙️ Tech Stack

| Layer | Technology |
|--------|-------------|
| **Frontend / Web App** | Laravel 12 |
| **API Service** | Node.js + Hono + Knex |
| **Database** | MySQL 8 |
| **Real-time Monitoring** | WebSocket / Event-driven architecture |
| **Bot Integration** | Telegram Bot API |

---

## 🧩 Features

- **Real-Time Dashboard:** Live monitoring of voltage, current, and power generation.  
- **Alert System:** Telegram bot integration for instant notifications.  
- **Data Visualization:** Interactive charts and analytics.  
- **Device Management:** Add, configure, and monitor solar units remotely.  
- **API Access:** RESTful and lightweight endpoints for data exchange.  

---

## 🛠️ Architecture Overview

```mermaid
graph TD
  A[ESP32 / Sensor Node] -->|Send Data| B[Node.js API (Hono + Knex)]
  B -->|Store Data| C[(MySQL Database)]
  B -->|Push Updates| D[Laravel Web Dashboard]
  D -->|Display Real-time Charts| E[User Interface]
  B -->|Send Alerts| F[Telegram Bot]

Installation
1. Clone Repository
git clone https://github.com/yourusername/rainergy-monitoring.git
cd rainergy-monitoring

2. Setup Backend (Node.js)
cd api
npm install
npm run dev

3. Setup Web App (Laravel)
cd web
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

4. Environment Configuration
Variable	Description
DB_HOST	Database host
DB_USER	Database username
DB_PASS	Database password
TELEGRAM_BOT_TOKEN	Telegram Bot Token
TELEGRAM_CHAT_ID	Telegram Chat ID for alerts
📡 Real-Time Monitoring

Real-time data transmission is handled via MQTT / WebSocket channels, ensuring the web dashboard receives continuous updates without refreshing.
Each panel unit periodically sends telemetry data to the Node.js API, which forwards it to the Laravel interface for visualization.

🤝 Collaboration

This project is part of the Rainergy Initiative, a collaborative innovation with
United Tractors (Astra Group) to promote sustainable, emission-free power systems.

🧾 License

This project is licensed under the MIT License — free to use, modify, and distribute with attribution.

<p align="center" style="color:#95a5a6;"> © 2025 Rainergy Project. Built with precision and purpose. </p> ```
