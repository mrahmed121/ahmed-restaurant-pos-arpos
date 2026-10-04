# ARPOS Architecture

## Domains
- **Restaurant**: Branches, dining tables
- **Menu**: Categories, items, modifiers
- **Orders**: Orders, items, payments, status workflow
- **Kitchen**: KOT ticket management
- **Inventory**: Ingredients, recipes (foundation)
- **Staff**: Employees, expenses (foundation)
- **Shared**: Auth, RBAC, audit, settings, tenancy

## Tenancy
Company → Branch. CompanyScope on all models. Cross-company returns 404.

## Key Workflows
1. **Order Flow**: POS → Order (pending) → KOT (kitchen) → preparing → ready → served → payment → completed
2. **Payment Flow**: Partial payments supported; overpayment blocked
