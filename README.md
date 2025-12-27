# DRAVEN — Deploy Control Plane

DRAVEN (*Deployment Routing & Approval Verification ENgine*) è un **Deploy Control Plane** per orchestrare rilasci su **più progetti** e **più ambienti** (stage, demo, production) con governance: **approvazioni**, **audit trail**, **lock per ambiente**, e integrazioni con **Bitbucket / GitHub / GitLab**.

Tagline: **“Controlled releases across stage, demo and production.”**

Licenza: **MIT**.

---

## Obiettivi

- Centralizzare in un’unica console l’avvio e il tracciamento dei deploy.
- Standardizzare il processo tra progetti diversi (stesso “contratto” di deploy).
- Aumentare sicurezza e affidabilità con policy, RBAC, lock, e audit.

---

## Funzionalità principali

- **Projects / Environments / Policies**: gestione centralizzata di progetti, ambienti e regole.
- **RBAC** (Role-Based Access Control): chi può deployare cosa e dove.
- **Deploy / Rollback / Dry-run**: job tracciati end-to-end.
- **Approval gates**: soprattutto su demo/production.
- **Environment lock**: niente deploy concorrenti sullo stesso ambiente.
- **Audit**: eventi rilevanti (chi ha fatto cosa, quando, su quale ref).
- **Integrations**: trigger pipeline/workflow esterni + webhook/polling per stati e link ai log.

---

## Architettura ad alto livello

DRAVEN separa **orchestrazione** ed **esecuzione**.

1. **DRAVEN Control Plane (Symfony 7.4)**

- UI + API
- CRUD: Projects / Environments / Policies / Integrations
- RBAC, approvazioni, lock, audit
- tracking dei job
- notifiche (Slack/Teams/email) (opzionale)

2. **SCM/Pipeline Adapters (Bitbucket/GitHub/GitLab)**
   Un livello di astrazione (es. `ScmProviderInterface`) per:

- trigger pipeline/workflow con variabili standard (`JOB_ID`, `ENV`, `REF`, `TYPE`)
- ricezione status via webhook (o polling come fallback)
- associazione link/log/artifact al job

3. **Runner self-hosted + Deployer**
   Dove “gira davvero” il deploy:

- runner con toolchain (`php`, `composer`, `dep`, `ssh`, ecc.)
- accesso di rete ai target (VPS/VM/Kubernetes)
- segreti gestiti come secrets del provider o via Vault/Secret Manager

> Nota di sicurezza: l’esecuzione di Deployer dentro una request web (controller → `dep deploy`) introduce rischi di sicurezza, timeout e concorrenza; va delegata a pipeline/runner o worker isolati.

---

## Flusso operativo

1. L’utente seleziona **Project + Environment + Ref** (branch/tag/sha).
2. DRAVEN crea un **DeployJob** (`queued`) e valida la **Policy**.
3. Se serve, DRAVEN crea una **ApprovalRequest** (`waiting_approval`).
4. DRAVEN acquisisce un **EnvironmentLock** (esecuzione esclusiva).
5. DRAVEN triggera la pipeline del provider con variabili standard.
6. La pipeline esegue:
   - checkout repo
   - build/install (es. `composer install`)
   - `dep deploy <env> --revision=<ref>` (o equivalente)
7. Il provider notifica DRAVEN (webhook) con esito + link ai log.
8. DRAVEN aggiorna stato, salva audit/log (se previsto), rilascia il lock e notifica.

---

## “Contratto” per i repository (standardizzazione)

Per mantenere la UI coerente su molti progetti, DRAVEN assume uno standard minimo per ogni repo:

- un entrypoint coerente (es. `deploy/run.sh` o `deploy/run.php`)
- task Deployer minimali e consistenti:
  - `deploy:info` (preflight)
  - `deploy`
  - `rollback`
- convenzione ambienti (stage/demo/prod) e naming uniforme

In questo modo DRAVEN passa solo **`ENV`** e **`REF`** senza conoscere dettagli specifici del singolo progetto.

---

## Modello dati

Entità principali (orientate a Doctrine):

- **Project**: applicazione deployabile (name, slug, active…)
- **Environment**: stage/demo/prod per progetto, collegato a una **Policy**
- **Policy**: regole in `rules_json` (ref consentiti, approval gate, finestre orarie…)
- **RepoIntegration**: provider (bitbucket/github/gitlab) + repo + mapping pipeline
- **DeployJob**: richiesta di deploy/rollback/dry-run con stato e metadati
- **ApprovalRequest / ApprovalDecision**: gestione approvazioni
- **EnvironmentLock**: lock esclusivo per ambiente
- **DeployLogLine**: log centralizzati (altrimenti solo link al provider)

---

## Policy baseline (stage / demo / production)

Policy di base, personalizzabili per progetto:

- **Stage**: branch (e opzionale sha), nessuna approvazione, CI richiesta, rollback abilitato.
- **Demo**: main/release (branch o tag), approvazione opzionale, CI richiesta, force deploy disabilitato.
- **Production**: solo tag versionati, approvazione obbligatoria (1–2), CI richiesta, finestra oraria opzionale, force deploy disabilitato.

Le policy definiscono regole coerenti con il livello di rischio di ciascun ambiente e possono essere affinate per esigenze specifiche.

---

## RBAC (ruoli e permessi)

Struttura dei ruoli di base:

- **Viewer**: dashboard + lettura run/audit/log
- **Operator**: può avviare deploy su stage/demo, vedere log, fare rollback dove consentito
- **Approver**: può approvare/rifiutare gate (tipicamente demo/prod)
- **Admin**: gestione progetti/ambienti/policy/integrations/utenti

Implementazione iniziale semplice: ruoli su User (es. `ROLE_DEPLOY_STAGE`, `ROLE_DEPLOY_DEMO`, `ROLE_DEPLOY_PROD`, `ROLE_APPROVE_PROD`).

---

## Accesso ai target: VPN vs Bastion

- **VPN**: accesso “di rete” (come essere in LAN) verso servizi privati (DB, UI interne, ecc.)
- **Bastion / Jump host**: un solo endpoint pubblico controllato per saltare su host interni (soprattutto SSH)

Nel contesto deploy:

- spesso **bastion** per i runner/CI
- spesso **VPN** per accessi umani (dev/ops) e strumenti interattivi

---

## Roadmap

**v1**

- CRUD: Project, Environment, RepoIntegration, Policy
- Create Job: deploy/rollback/dry-run
- Lock per ambiente
- Approval gate per demo/prod
- Webhook receiver (con firma/secret)
- Stato job + link esterno ai log

**v2**

- log centralizzati (streaming o ingest eventi)
- reportistica (failure rate, tempi medi)
- finestre orarie avanzate e regole più ricche
- notifiche e integrazione incident management

---

## Nomenclatura UI (coerenza termini)

- **Project**: applicazione deployabile
- **Environment**: stage/demo/production
- **Run**: esecuzione tracciata (job)
- **Approval**: decisione che sblocca una run
- **Integration**: connessione provider SCM/CI
- **Policy**: regole di rilascio
- **Lock**: blocco esclusivo di un environment
