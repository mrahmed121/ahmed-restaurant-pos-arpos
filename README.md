# ACMS — Ahmed Clinic Management System

**"Ahmed — Care, Coordinated."**

**Developed by Ahmed**

## Overview

ACMS is a complete clinic management system for clinics and small hospitals. It manages patients, doctors, appointments, pharmacy, and clinical workflows.

## Features

- **Patient Management** — Records with medical history, search, visit timeline
- **Appointment Scheduling** — Doctor availability with conflict detection
- **Doctor Management** — Specializations, departments, consultation fees
- **Pharmacy** — Medicine inventory with low-stock alerts
- **Prescriptions** — Linked to appointments (foundation)
- **Dashboard** — Real query-backed stats
- **RBAC** — 8 roles with granular permissions
- **Audit Logging** — All clinical/financial operations tracked

## Tech Stack

- Backend: Laravel 11 API + JWT
- Frontend: React 18 + Vite + Tailwind

## Quick Start

```bash
cd backend && composer install && cp .env.example .env
php artisan key:generate && php artisan jwt:secret
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8002

cd frontend && npm install
echo "VITE_API_BASE_URL=http://127.0.0.1:8002/api/v1" > .env
npm run dev -- --host 127.0.0.1 --port 5175
```

## Demo (password: password123)

| Role | Email |
|------|-------|
| Admin | admin@ahmedclinic.local |
| Doctor | doctor@ahmedclinic.local |
| Receptionist | receptionist@ahmedclinic.local |
| Pharmacist | pharmacist@ahmedclinic.local |

## License & Attribution

MIT License.

**Based on:** [klinik-laravel-api](https://github.com/IslamTaleb11/klinik-laravel-api) by Islam Taleb, MIT License (Copyright (c) 2025 Islam Taleb).

**Substantially modified by Ahmed:** Complete product rebuild — new React 18 SPA frontend (upstream is API-only); JWT + granular RBAC (8 roles); appointment conflict detection; patient visit timeline; pharmacy low-stock alerts; analytics dashboard; audit logging; Ahmed design system. Upstream's clinic domain concepts informed the design; all code is newly written.

Original MIT LICENSE preserved in `LICENSE-UPSTREAM.md`.
