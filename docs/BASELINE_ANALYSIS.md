# Baseline Analysis — faizaldevs/RestoPOS (upstream)

**Source:** https://github.com/faizaldevs/RestoPOS
**Author:** Anowar Hossain (faizaldevs)
**License:** MIT (Copyright (c) 2020 Anowar Hossain)
**Stars:** 104 | **Pushed:** 2025-03-10

## What It Does
SaaS multi-tenant restaurant billing & management system.
- Laravel 8 backend, Vue.js frontend, AdminLTE UI
- Stancl Tenancy (multi-tenancy), Laravel Passport (API auth)
- POS billing, Kitchen Order Tickets (KOT), recipe management with auto ingredient deduction
- Expenses, profit & loss reports, employee management, account management
- Multiple payment methods, split payments, thermal printing
- Order types: dine-in, takeaway, delivery, pre-booking; table management

**Scale:** 43 models, 58 controllers, 13 test files, 9 migrations (consolidated).

## Execution Baseline
**NOT EXECUTED** — Laravel 8 dependencies conflict with PHP 8.3 environment (lcobucci/jwt/clock version incompatibility). Static analysis performed instead.

## Strengths (Keep Conceptually)
- Real restaurant operations depth: POS → KOT → recipe deduction → P&L
- Multi-tenancy architecture (Stancl)
- API authentication (Passport)
- Split payments, multiple order types

## Weaknesses / Gaps
- Laravel 8 (EOL), Vue 2, AdminLTE (dated UI)
- Only 13 test files for 58 controllers (thin coverage)
- No granular RBAC (basic roles)
- No analytics dashboard with real queries
- No audit logging
- No CSV import/export
- No approval workflows

## Ahmed Transformation Plan
**Product:** Ahmed Restaurant POS (ARPOS)
**Tagline:** "Ahmed — Serve Every Order With Precision."
**Stack:** Laravel 11 API (PHP 8.3) + React 18 SPA + JWT + RBAC
**Repo:** ahmed-restaurant-pos-arpos

**Strategy:** Domain model ported from upstream; full rebuild on Ahmed's proven stack (not a Laravel 8→11 upgrade — a clean reimplementation).

**10 Differentiators:**
1. Modern Laravel 11 API + React 18 SPA (vs Laravel 8 + Vue 2 + AdminLTE)
2. JWT auth + granular RBAC permission matrix (vs Passport + basic roles)
3. Real-time KOT (Kitchen Order Ticket) display with status workflow
4. Recipe-based auto ingredient deduction with stock alerts
5. Analytics dashboard (sales trends, top items, peak hours — real queries)
6. Audit timeline for all financial operations
7. CSV import (menu items, employees) + PDF exports (receipts, reports)
8. Approval workflows (discounts, voids, refunds)
9. Advanced search & filters (orders, menu, employees)
10. Multi-branch support with branch isolation

**License compliance:** MIT LICENSE preserved with original copyright, attribution section in README.
