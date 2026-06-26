# Post-Deploy Verification

## Objective
Provide a deterministic verification sequence after each production deployment.

## 1. Service Health
Run:

```bash
curl -i http://127.0.0.1/health
```

Expected:
- HTTP status `200`.
- JSON response with `status: "ok"`.
- Database, Redis, Queue and Storage checks in healthy state.

## 2. Application Runtime
Run:

```bash
php artisan about
php artisan queue:monitor redis:default --max=100
```

Verify:
- Application boots without fatal errors.
- Queue backend reachable.

## 3. Core Role Access
Validate login and dashboard access for:
- `super_admin`
- `company` (owner/admin role)
- `technician`
- `customer`

Expected:
- Correct portal redirect by role.
- No cross-portal access.

## 4. Critical Business Flow
Validate one end-to-end flow:
- Ticket creation
- Diagnostic update
- Quote submission
- Repair update
- Closure

Expected:
- State transitions are consistent.
- Tenant data isolation is preserved.

## 5. AI Assistant Validation
Verify:
- Widget visibility by portal policy.
- Role-aware response context is applied.
- Conversation history is isolated by user/company.

## 6. Logs and Errors
Run:

```bash
tail -n 100 storage/logs/laravel.log
```

Expected:
- No repeated fatal errors.
- No permission denied errors in `storage` or `bootstrap/cache`.

## 7. Rollback Trigger Conditions
Initiate rollback when any of the following is true:
- `/health` is not `200`.
- Authentication or role routing is broken.
- Critical flow cannot be completed.
- Repeated fatal errors continue after service restart.

## 8. Deployment Sign-off Template
- Deployment date/time:
- Commit hash:
- Operator:
- Health check status:
- Critical flow status:
- AI assistant status:
- Final decision: `APPROVED` / `ROLLBACK`

