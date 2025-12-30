# Ipotesi di architettura — DRAVEN Deploy Control Plane

## Architettura target

### 1) Symfony 7.4 “DRAVEN Deploy Control Plane”
Responsabilità:
- UI + API
- gestione **Projects / Environments / Policies**
- **RBAC** (chi può deployare cosa e dove)
- creazione e tracking dei **Deploy Job**
- **approvazioni**
- log centralizzati e audit trail
- notifiche (Slack/Teams/email)

### 2) “SCM/Pipeline Adapters” (Bitbucket/GitHub/GitLab)
Symfony espone un livello di astrazione `ScmProviderInterface` con implementazioni:
- BitbucketAdapter
- GitHubAdapter
- GitLabAdapter

Compiti degli adapter:
- trigger pipeline/workflow passando variabili (project, env, ref, **job_id**)
- ricezione dello status (webhook) o polling di fallback
- associazione di output/log/artifacts al job

### 3) Runner (self-hosted) + Deployer
Ambiente di esecuzione del deploy:
- runner con `php`, `composer`, `dep`, ssh client, eventuale `node`, `kubectl`
- accesso di rete ai target (VPS/cloud)
- segreti gestiti come **secrets del provider** oppure tramite vault esterno

> Nota operativa: con VPS e cloud misti è utile mantenere runner self-hosted in una rete controllata (o con VPN/bastion) per gestire la connettività verso i target.

---

## Flusso operativo previsto
1. L’utente seleziona progetto, ambiente e ref (branch/tag/commit) in Symfony.
2. Symfony crea un `DeployJob` con stato `PENDING`.
3. Symfony valida la policy applicata (es. produzione solo tag, demo solo branch develop).
4. Symfony acquisisce un **lock** per `(project, environment)` (uno alla volta su produzione).
5. Symfony triggera la pipeline del provider con variabili:
   - `JOB_ID`
   - `ENV=stage|demo|prod`
   - `REF=tag/branch/sha`
6. La pipeline esegue:
   - checkout del repo
   - `composer install` (o uso di artifact precompilato)
   - `dep deploy ENV --revision=REF`
   - callback verso Symfony con esito e link log
7. Symfony aggiorna il job, salva log, rilascia il lock e invia notifiche.

---

## Standardizzazione cross-progetto
Con circa 10 progetti è necessario uno standard minimo per mantenere coerente la UI.

### “Contratto” per ogni repo
- file di entrypoint uniforme, ad esempio `deploy/run.sh` o `deploy/run.php`
- set minimo di task Deployer allineati:
  - `deploy:info` (preflight)
  - `deploy` (deploy)
  - `rollback`
- convenzione ambienti (stage/demo/prod) e naming coerente

Symfony passa solo `ENV` e `REF` senza conoscere le specificità del singolo progetto.

---

## Gestione VPS e Cloud
**VPS (SSH)**:
- target definiti come host SSH
- chiave deploy per ambiente o progetto, con permessi minimi

**Cloud**:
- VM (EC2, GCE, ecc.): accesso via SSH come per VPS
- Kubernetes / servizi gestiti: Deployer lancia task `kubectl/helm` con credenziali sul runner, oppure delega a uno script di deploy specializzato richiamato dalla pipeline

L’esecuzione sui runner consente di trattare i target come driver diversi mantenendo un unico flusso di orchestrazione.

---

## Sicurezza
Per un pannello deploy la sicurezza è parte del prodotto.

Requisiti minimi:
- RBAC con separazione della produzione
- audit completo (chi, cosa, quando, ref)
- produzione solo tag o release branch, preferibilmente tag firmati
- approvazione per produzione (con supporto a 2-person rule)
- lock per ambiente
- segreti non memorizzati in chiaro nel DB (se salvati: cifrati e protetti da KMS/Vault)

---

## Integrazione Bitbucket, GitHub e GitLab
Configurazione prevista:
- Symfony supporta più “RepoIntegration” per progetto:
  - provider (bitbucket/github/gitlab)
  - identificativo del repo
  - metodo trigger pipeline
  - mapping ambienti → pipeline/workflow
- ogni provider usa un template pipeline compatibile con il contratto (`deploy/run.*`)

La migrazione di un progetto tra provider avviene modificando l’integrazione senza cambiare il control-plane.

---

## Attività operative iniziali
1) **Definire il perimetro v1**
- deploy manuale da UI
- eventuali schedulazioni
- branch su demo e tag su produzione

2) **Scegliere la modalità log**
- pipeline che invia eventi (start/end/step) e linka i log del provider
- pipeline che invia log a Symfony (webhook) per archiviazione interna

3) **Impostare l’abstraction layer provider**
- utilizzare l’entità `RepoIntegration` con adapter per trigger e webhook receiver

4) **Proof of concept su 1 progetto**
- 1 progetto, 2 ambienti (demo/prod), 1 provider (Bitbucket)
- locking, policy e job tracking
- rollback

Dopo il primo progetto il setup diventa replicabile sugli altri tramite configurazione.

---
