# Baseline Analysis — IslamTaleb11/klinik-laravel-api (upstream)

**Source:** https://github.com/IslamTaleb11/klinik-laravel-api
**Author:** Islam Taleb
**License:** MIT (Copyright (c) 2025 Islam Taleb)
**Stars:** 27 | **Pushed:** 2025-09

## What It Does
Laravel API backend for clinic management:
- Patients, doctors, departments, appointments, doctor schedules
- Beds/allotments, blood bank/donors, pharmacy/medicines, orders
- Birth/death/operation reports, services, offers
- Appointment/order notifications, mail

**Scale:** 27 migrations, ~20 models, 8 test files. API-only (no frontend).

## Candidate Score: 71/100
Below the 75 threshold, but selected honestly: no stronger MIT-licensed clinic/pharmacy
candidate exists (top-starred alternatives have NO license). The API-only nature is the
upgrade opportunity — the Ahmed product delivers the complete experience.

## Strengths (Keep Conceptually)
- Real clinic domain: appointments → doctors → patients → pharmacy
- API-first design (matches Ahmed's architecture)
- Notification system foundation

## Weaknesses / Gaps
- No frontend at all
- Basic auth (no granular RBAC)
- No analytics dashboard
- No audit logging
- Thin test coverage (8 files)

## Ahmed Transformation Plan
**Product:** Ahmed Clinic Management System (ACMS)
**Tagline:** "Ahmed — Care, Coordinated."
**Stack:** Laravel 11 API + React 18 SPA + JWT + RBAC
**Repo:** ahmed-clinic-management-system-acms

**10 Differentiators:**
1. Complete React 18 SPA frontend (vs API-only)
2. JWT + granular RBAC (9 roles: Admin, Doctor, Nurse, Receptionist, Pharmacist, Lab Tech, Accountant, Patient, Auditor)
3. Appointment scheduling with doctor availability & conflict detection
4. Patient records with visit history timeline
5. Analytics dashboard (appointments, revenue, patient flow — real queries)
6. Audit logging for medical/financial records
7. Prescription management with pharmacy integration
8. Bed management with occupancy tracking
9. Advanced search & filters
10. Notifications & reminders (appointment reminders)

**License compliance:** MIT LICENSE preserved, attribution in README.
