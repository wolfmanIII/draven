# DRAVEN — Dettaglio `rules_json` per le Policy

Guida di riferimento per la configurazione del campo `rules_json` nelle policy ambiente. Gli esempi sono allineati alle baseline stage/demo/prod in `DRAVEN_db-schema-flow-policy.md`.

---

## Chiavi disponibili

- **`allowed_ref_types`** (array di string): tipi di ref ammessi. Valori consentiti: `branch`, `tag`, `sha`. Campo obbligatorio e non vuoto.

- **`allowed_branches`** (array di string, opzionale): attivo solo se `branch` è tra i tipi ammessi. Ogni voce può essere:
  - stringa letterale (match esatto), es. `"develop"`;
  - espressione regolare, es. `"feature/.*"` per qualunque branch che inizi con `feature/`.
  - Nota regex: in JSON i backslash vanno raddoppiati, es. `\\d` per indicare un numero.

- **`allowed_tags`** (array di string, opzionale): analogo a `allowed_branches`, applicato ai tag. Stringhe letterali o regex.
  - Esempio semver: `"v\\d+\\.\\d+\\.\\d+"` (corrisponde a `vX.Y.Z`).
  - Esempio formato data: `"\\d{4}\\.\\d{2}\\.\\d{2}"` (corrisponde a `YYYY.MM.DD`).

- **`require_approval`** (bool): abilita il gate di approvazione (`waiting_approval`).

- **`approvals_required`** (int): numero di approvazioni richieste. Se `require_approval` è `false`, trattare come 0.

- **`require_ci_success`** (bool): richiede esito CI positivo sul ref.

- **`allow_rollback`** (bool): abilita il rollback.

- **`allow_force_deploy`** (bool): consente deploy forzati (da usare con cautela; sconsigliato in produzione).

- **`restrict_deploy_window`** (oggetto, opzionale): finestra temporale ammessa.
  - `tz`: timezone IANA (es. `Europe/Rome`).
  - `days`: array di interi che rappresentano i giorni ammessi (es. `[1,2,3,4,5]` per lun–ven).
  - `from` / `to`: orari `HH:MM` con `from` precedente a `to`.

---

## Validazione e raccomandazioni

1. `allowed_ref_types`: contiene solo valori ammessi (`branch|tag|sha`) e almeno un elemento.
2. `allowed_branches`/`allowed_tags`: usare stringhe o regex esplicite. Esempi:
   - Liste chiuse: `["develop", "main"]`
   - Pattern: `["release/.*", "hotfix/.*"]` oppure `"v\\d+\\.\\d+\\.\\d+"` per tag semver.
3. `approvals_required` ≥ 0; se `require_approval` è `false`, considerare `approvals_required=0`.
4. `restrict_deploy_window`: timezone valida, giorni nel range, orari `HH:MM` con `from < to`.
5. `allow_force_deploy`: mantenerlo `false` negli ambienti critici.

---

## Esempi commentati

### Stage (sviluppo continuo)
```json
{
  "allowed_ref_types": ["branch", "sha"],
  "allowed_branches": ["develop", "feature/.*", "bugfix/.*"],
  "require_approval": false,
  "approvals_required": 0,
  "require_ci_success": true,
  "allow_rollback": true,
  "allow_force_deploy": true
}
```
- Consente branch di sviluppo e commit specifici, niente approvazioni.
- CI richiesta per evitare rotture ripetute.
- Force deploy abilitato (ambiente non critico).

### Demo (ambiente controllato)
```json
{
  "allowed_ref_types": ["branch"],
  "allowed_branches": ["main", "release/.*"],
  "require_approval": true,
  "approvals_required": 1,
  "require_ci_success": true,
  "allow_rollback": true,
  "allow_force_deploy": false
}
```
- Solo branch stabili (main/release).
- Una approvazione richiesta prima del deploy.
- CI obbligatoria, rollback permesso, no force deploy.

### Produzione (alta restrizione)
```json
{
  "allowed_ref_types": ["tag"],
  "allowed_tags": ["v\\d+\\.\\d+\\.\\d+"],
  "require_approval": true,
  "approvals_required": 2,
  "require_ci_success": true,
  "allow_rollback": true,
  "allow_force_deploy": false,
  "restrict_deploy_window": {
    "tz": "Europe/Rome",
    "days": [1, 2, 3, 4, 5],
    "from": "09:00",
    "to": "19:00"
  }
}
```
- Solo tag versionati semver (regex con backslash doppi).
- Due approvazioni richieste, CI obbligatoria, rollback permesso.
- Finestra oraria lavorativa; force deploy disabilitato.

---

## Estensioni opzionali
- `allow_previous_release`: abilita rollback alla release precedente.
- `notify_channels`: lista canali notifiche (es. `["slack:deploys", "email:ops@company.com"]`).
- `approval_roles`: ruoli abilitati alle approvazioni (es. `["ROLE_APPROVE_PROD"]`).

---

## Note operative
- Regex in JSON: raddoppiare i backslash (`\\d`), validare e sanificare lato backend.
- Mantenere le baseline (stage/demo/prod) come seed iniziale e specializzare per progetto.
- Documentare le eccezioni (es. force deploy) e limitarle ai ruoli autorizzati.
