# DRAVEN
## Nome e significato
DRAVEN è il nome del Deploy Control Plane.

**DRAVEN** è l’acronimo di:
**D**eployment **R**outing & **A**pproval **V**erification **EN**gine

---

## Posizionamento
DRAVEN è una console centrale per orchestrare rilasci su **più progetti** e **più ambienti** (stage, demo, produzione), con:
- approvazioni (gating)
- audit trail
- lock per ambiente
- integrazioni SCM/CI (Bitbucket, GitHub, GitLab)
- deploy eseguiti su runner (Deployer)

---

## Naming “da prodotto”
Diciture utilizzate in modo coerente:
- **DRAVEN Deploy Control Plane** (nome del prodotto)
- **DRAVEN Deploy Console** (nome dell’interfaccia web)
- **DRAVEN Mission Control** (nome UI alternativo)

---

## Tagline
**“Controlled releases across stage, demo and production.”**

---

## IA UI (sezioni principali)
Menu e pagine principali (inglese tecnico):

1. **Dashboard**
   - stato generale (ultimi job, job in corso, failure rate, ambienti bloccati)

2. **Projects**
   - elenco progetti, dettagli progetto

3. **Environments**
   - stage / demo / production per progetto
   - policy attiva e stato lock

4. **Runs**
   - lista dei job (deploy/rollback/dry-run)
   - filtri: progetto, ambiente, stato, utente, ref

5. **Approvals**
   - richieste in attesa
   - storico approvazioni

6. **Integrations**
   - Bitbucket / GitHub / GitLab
   - mapping pipeline/workflow per ambiente

7. **Policies**
   - regole di branch/tag
   - finestre orarie (se applicate)

8. **Audit**
   - eventi rilevanti (chi ha fatto cosa, quando)

9. **Settings**
   - utenti/ruoli, notifiche, retention log

---

## Nomenclatura interna (coerenza dei termini)
Terminologia uniforme in UI e documentazione:
- **Project**: applicazione deployabile
- **Environment**: stage, demo, production
- **Run**: esecuzione tracciata (job)
- **Approval**: decisione umana che sblocca una run
- **Integration**: connessione al provider SCM/CI (Bitbucket/GitHub/GitLab)
- **Policy**: regole che limitano cosa si può rilasciare e come
- **Lock**: blocco esclusivo di un environment per evitare concorrenti

---

## Glossario (UI + documentazione)
### Deploy
Esecuzione che porta una versione (branch/tag/SHA) su un ambiente.

### Rollback
Esecuzione che riporta l’ambiente a una release precedente (o al “previous release” gestito da Deployer).

### Dry-run
Simulazione o preflight: validazioni e previsione degli effetti senza applicare modifiche.

### Ref
Riferimento Git selezionato per il rilascio.
- **Branch**: flusso continuo (tipico di stage/demo)
- **Tag**: release versionata (tipico di produzione)
- **SHA**: commit specifico (debug o hotfix controllato)

### Gate / Approval Gate
Punto di controllo umano prima di far partire una run (soprattutto in produzione).

### Audit trail
Registro immodificabile degli eventi: richiesta run, approvazioni, trigger pipeline, esito, rollback.

### External run
Esecuzione lato provider (pipeline/workflow) triggerata da DRAVEN.

---

## Mini brand guide (pratica, pronta per UI)

### Tone of voice
- tecnico, asciutto, senza slang
- messaggi di errore utili e azionabili
- azioni critiche sempre confermate (deploy prod, rollback prod)

### UI style
- look “console” moderno: pulito, scuro o chiaro ma coerente
- attenzione ai badge di stato e ai log

### Palette (2 colori + neutrali)
Palette a due colori principali con neutrali:
- **Primary**: blu profondo (azioni principali, link, CTA)
- **Accent**: ciano/teal (stato “running”, elementi sci‑fi discreti)
- **Warning**: ambra (approvazione richiesta, attenzione)
- **Danger**: rosso (failed, stop, rollback)
- **Success**: verde (success)

### Iconografia
- Deploy: razzo / upload
- Rollback: undo
- Approval: check / shield
- Lock: lucchetto
- Integration: plug
- Audit: scroll / list

---

## Sezioni standard nelle pagine (pattern di layout)
Ogni pagina principale segue:
1. **Header**: titolo + breadcrumbs
2. **Context bar**: progetto + ambiente selezionati
3. **Actions**: pulsanti (Deploy, Dry-run, Rollback) con permessi
4. **Details**: policy, lock, ref consentiti
5. **History**: ultime run e link ai log esterni

---

## Output minimo “v1” da mostrare in UI
Per ogni Run mostrare:
- Project / Environment
- Type (deploy/rollback/dry-run)
- Ref (branch/tag/sha)
- Requested by
- Status
- Started/Finished + durata
- External run URL
- Summary / Error message

---

## Decisione finale
Nome ufficiale del sistema:

**DRAVEN — Deployment Routing & Approval Verification ENgine**
