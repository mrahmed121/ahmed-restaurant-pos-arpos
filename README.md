# ARPOS — Ahmed Restaurant POS

**"Ahmed — Serve Every Order With Precision."**

**Developed by Ahmed**

## Overview

ARPOS is a complete restaurant point-of-sale and management system for restaurants, cafés, and food businesses. It handles the full order lifecycle: menu management → POS ordering → kitchen display → payments → reporting.

## Features

- **POS Terminal** — Fast order creation with menu browsing, cart, and order types (dine-in, takeaway, delivery)
- **Kitchen Display (KDS)** — Live order tickets with status workflow (pending → preparing → ready → served), auto-refresh
- **Menu Management** — Categories, items, modifiers, pricing
- **Order Management** — Full lifecycle with status tracking
- **Payments** — Cash, card, mobile with split payment support
- **Branches** — Multi-branch support with isolation
- **Tables** — Dining table management
- **Reports** — Sales analytics with real queries (revenue, orders, top items, by day/type)
- **RBAC** — 9 roles with granular permissions
- **Audit Logging** — All financial operations tracked

## Tech Stack

- Backend: Laravel 11 API + JWT
- Frontend: React 18 + Vite + Tailwind
- Database: SQLite / MySQL / PostgreSQL

## Quick Start

```bash
# Backend
cd backend && composer install && cp .env.example .env
php artisan key:generate && php artisan jwt:secret
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8001

# Frontend
cd frontend && npm install
echo "VITE_API_BASE_URL=http://127.0.0.1:8001/api/v1" > .env
npm run dev -- --host 127.0.0.1 --port 5174
```

## Demo Accounts (password: password123)

| Role | Email |
|------|-------|
| Owner | owner@ahmedfoods.local |
| Branch Manager | manager@ahmedfoods.local |
| Cashier | cashier@ahmedfoods.local |
| Waiter | waiter@ahmedfoods.local |
| Kitchen Staff | kitchen@ahmedfoods.local |
| Accountant | accountant@ahmedfoods.local |

## License & Attribution

This project is licensed under the MIT License.

**Based on:** [RestoPOS](https://github.com/faizaldevs/RestoPOS) by Anowar Hossain, MIT License (Copyright (c) 2020 Anowar Hossain).

**Substantially modified by Ahmed:** Complete architectural rebuild from Laravel 8 + Vue 2 + AdminLTE to Laravel 11 API + React 18 SPA; JWT authentication replacing Passport; granular RBAC permission matrix (9 roles); new kitchen display system; new analytics reporting engine; new audit logging; new branch isolation model; Ahmed design system UI. The upstream's restaurant domain concepts (POS flow, KOT, menu structure) informed the design; all code is newly written.

Original MIT LICENSE and copyright notice are preserved in `LICENSE-UPSTREAM.md`.
