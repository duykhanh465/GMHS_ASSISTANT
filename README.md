# GMHS Assistant

Clinical decision-support PWA for anesthesia & ICU (Vietnamese hospital workflow). Vue 3 + Laravel 12 + MySQL. Doses and ventilator numbers are **suggestions only** — the attending physician decides.

Excel source file stays local (`docs/clinical/excel-original/`) and is **not** pushed to GitHub.

## Stack

- Backend: Laravel 12, PHP 8.3, Sanctum, MySQL (Laragon)
- Frontend: Vue 3.5, TypeScript, Pinia, Vue Router, Axios, Tailwind 3, Vite
- PWA: `manifest.webmanifest` + `sw.js` (app shell cache, API always network)

## Run locally

Two terminals.

```bash
# backend  http://127.0.0.1:8000
cd backend
php artisan migrate
php artisan db:seed
php artisan serve
```

```bash
# frontend  http://localhost:5173
cd frontend
npm install
npm run dev
```

Demo accounts (local only):

| Role   | Email              | Password |
|--------|--------------------|----------|
| admin  | admin@gmhs.test    | password |
| doctor | doctor@gmhs.test   | password |

Each browser session starts at **Login**. Token lives in `sessionStorage` (`gmhs_token`) so a new tab / close-window requires login again.

## Golden numbers (DEMO-001)

Female, 80 y, 150 cm, 65 kg, ASA II, routine:

- BMI 28.9 · PBW 43.3 kg · AdjBW 52 kg
- Fluid 4-2-1 = 105 mL/h
- Vt 260–303 mL · RR 14 · PEEP 5 · FiO₂ 40–60%

## Roles

- **Doctor** — own patients only, calculate, read drugs / ventilator / spinal / crisis / sources.
- **Admin** — all of the above plus `/admin` (users, activate/deactivate drugs, sources).

## API (Sanctum)

- `POST /api/login` · `GET /api/me` · `POST /api/logout`
- `apiResource patients` + `POST /patients/{id}/calculate` + history
- `GET /drugs` `GET /ventilator-presets` `GET /sources`
- `GET /spinal-protocols` `GET /emergency-protocols` — Excel sheets Gay_te_TTS / Cap_cuu_CRISIS (7 + 7 rows)
- `?locale=` overlays English (and category names in 8 UI languages)
- ` /api/admin/*` — middleware `admin`

## Frontend pages

Overview, Patients, Anesthesia, Ventilator, Vasopressor, Pain & PONV, Spinal, CRISIS, Formulas, References, Settings (8 languages + dark mode), Admin (admin role).

Installable on phone: Chrome/Edge → Install app; iOS Safari → Add to Home Screen.

## Tests

```bash
cd backend && php artisan test
cd frontend && npm run test:unit && npm run build
```

## Do not commit

- `backend/.env`, `vendor/`, `node_modules/`
- `docs/clinical/excel-original/`
- Real patient identifiers (use `DEMO-001` only)
