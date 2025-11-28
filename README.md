# Life Atlas — All‑In‑One Personal Life Management Platform

**Status: Under Active Development 🚧**

Life Atlas is a comprehensive, modular, and fully‑customizable personal life‑management platform built with **pure PHP (OOP + MVC)**. It centralizes every aspect of your personal, financial, and productivity life into one secure and intelligent system.

This project is designed for extensibility, clean architecture, and high maintainability—making it suitable for both personal use and further open‑source development.

---

## 🚀 Key Features

### 🔐 Authentication & Security
- Secure user registration and login
- Email verification (coming soon)
- Password reset system
- Role‑based access control (Admin / User)
- CSRF protection, XSS filtering, and prepared statements
- Encrypted password storage (BCRYPT)

### 🏠 Dashboard
- Real‑time statistics
- Upcoming bills, tasks, and events overview
- Gamification progress summary
- Quick actions panel
- Activity and notification feed

### 🤖 AI Assistant Module
- Chat-based AI assistant (OpenAI powered)
- Conversation history
- Productivity suggestions & financial insights
- Modular integration for future AI providers

### 🕹 Gamification Engine
- XP-based reward system
- Leveling system
- Streak tracking (daily/weekly)
- Achievement badges
- Leaderboard-ready backend

---

## 💰 Financial Modules
- **Bills Manager** — recurring, one‑time, overdue alerts
- **Budget Planner** — category-based monthly budgeting
- **Subscriptions Manager** — renewal reminders, monthly cost tracking
- **Crypto Portfolio** — holdings tracking (API-ready)
- **Debt Tracker** — loans, credit cards, payoff progress
- **Asset Management** — properties, vehicles, investments

## 📅 Productivity Modules
- **Task Manager** — priority, status, deadlines
- **Project Manager** — grouped task workflows
- **Time Tracking** — timer + logs
- **Calendar & Events** — reminders + scheduling
- **Notes** — text & voice-ready architecture
- **Contract Manager** — document deadlines & metadata

## 👤 Personal Life Modules
- Contacts & birthdays
- Relationship tracker
- Pet care & vet reminders
- Reading list & library
- Travel planner
- Vehicle service & insurance tracking
- Password manager (secure, encrypted)

---

## 📁 File & Document System
- Secure file uploads
- File type/size validation
- Categorized document storage
- Download & delete permissions
- Audit logging

---

## 🔔 Notification System
- In-app notification center
- Mark/read status
- AJAX‑based live updates

---

## 📊 System Analytics
- User activity logs
- XP transaction history
- Module usage insights
- Admin analytics dashboard

---

## 🧱 Tech Stack
- **Backend:** PHP 8.2 (Pure PHP, OOP, MVC)
- **Database:** PostgreSQL
- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JS
- **Security:** CSRF, prepared statements, sanitized inputs

---

## 📦 Installation

### Prerequisites
- PHP 8.2+
- PostgreSQL
- Apache/Nginx (or built‑in PHP server)

### Steps
1. **Clone the repository**
```bash
git clone <repository-url>
cd life-atlas
```

2. **Configure the environment**
- Database credentials via environment variables
- Upload limits
- Optional: Add `OPENAI_API_KEY` for AI module

3. **Start the application**
- Run via PHP server or local hosting
- Access `/register` to create your first account

---

## 📂 Project Structure
```
app/                # Controllers, Models, Views, Core
config/             # App & DB configurations
database/           # Schema & migrations
public/             # Entry point + assets
uploads/            # User documents & images
logs/               # System logs
```

---

## 🔐 Security Highlights
- Fully prepared‑statement DB layer
- Strong session management
- Form validation & sanitization
- File upload restrictions
- Role-based authorization

---

## 📈 Gamification System
**XP Rewards:** tasks, bills, uploads, logins, AI usage, and more.

**Leveling:** 1000 XP per level.

**Achievements:** unlockable system ready for expansion.

---

## 🔌 API Endpoints (Core)
- `/login` — authenticate
- `/register` — create account
- `/dashboard` — main interface
- `/bills`, `/tasks`, `/projects`, `/subscriptions`, `/contacts`
- `/documents` — file manager
- `/notifications` — notification center
- `/api/notifications/unread` — JSON endpoint

---

## 🛠 Future Enhancements
- Email verification system
- Two-factor authentication
- Dark mode
- PDF/CSV exporting
- Multi-language support
- Mobile apps (Flutter/React Native)
- AI voice commands
- OCR document scanning

---

## 🤝 Contribution
Pull requests will be welcomed once the system reaches **v1.2+**.

Currently, the project is **under active development** and undergoing structural improvements.

---

## 📜 License
This project is proprietary. All rights reserved.

See **LICENSE.md** for full terms.

---

## 📌 Version Info
**Version:** 1.0.0 (Early Build)  
**Status:** Under Development  
**Last Updated:** November 2025

