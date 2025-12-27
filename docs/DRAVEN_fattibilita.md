# Fattibilità — DRAVEN Control Plane (Deployer)

Control plane orientato al governo dei rilasci: ruoli, audit, approvazioni, log centralizzati, standardizzazione dei rilasci, rollback “a bottone”.

**Da evitare:** eseguire Deployer dentro la request web (controller → `dep deploy`), per non introdurre rischi di sicurezza, timeout, concorrenza e affidabilità.

---

## Ambito di utilizzo
- Contesti con **più progetti/ambienti** (dev/test/stage/prod) e necessità di una UI unica.
- Requisiti di **RBAC**, approvazioni, tracciabilità e log/metriche di deploy.
- Necessità di una procedura standard su più progetti (hook, check, rollback).

Fuori ambito:
- 1–2 progetti con un solo ambiente e team ridotto dove CI/CD + Deployer CLI è sufficiente.
- Scenari in cui non si intende gestire segreti/permessi in modo centralizzato.

---

## Architetture ipotesi (ordine di isolamento)

### A) Symfony come orchestratore che triggera una pipeline CI/CD
Symfony non esegue Deployer, ma:
- crea un Deploy Job a DB
- chiama Bitbucket / GitLab CI / GitHub Actions / Jenkins (API) passando parametri (progetto, ambiente, ref/tag)
- la pipeline esegue Deployer su un runner dedicato
- la pipeline rimanda a Symfony stato e log (webhook o polling)

Vantaggi:
- chiavi SSH e permessi restano nel runner/CI, non nel web server
- migliore scalabilità e minore esposizione security
- deploy ripetibili e allineati all’infrastruttura

Limiti:
- integrazione API e gestione log più articolata

### B) Symfony esegue Deployer tramite worker dedicati
Symfony gestisce UI e Jobs, poi:
- un worker (Symfony Messenger) preleva il job
- esegue `vendor/bin/dep deploy …` via `Process`
- salva output e status

Condizioni operative:
- worker su macchina “runner” separata (o container isolato), non sul frontend
- chiavi SSH protette (Vault/secret manager + encryption at rest)
- locking per ambiente (un deploy alla volta su produzione)
- timeouts e kill gestiti

---

## Requisiti essenziali

### 1) Modello dati minimo
- **Project** (nome, repo, tipo recipe)
- **Environment** (dev/stage/prod, hosts, branch/tag policy)
- **DeployJob** (richiedente, ref, status, durata)
- **DeployLog** (output streaming, step, errori)
- **User / Role / Permission** (RBAC)

### 2) Sicurezza
- **Least privilege**: utente SSH “deploy” con permessi minimi sui target
- **Segreti**: chiavi SSH cifrate, preferibilmente gestite da Vault o secret manager
- **Audit**: chi ha fatto cosa, quando, su quale ref
- **Policy**: in produzione solo tag firmati / release branch / approvazione obbligatoria
- **Hardening**: niente comandi arbitrari passati dall’UI

### 3) Affidabilità operativa
- **Queue** per i job (Messenger + trasporto robusto)
- **Locking** per ambiente
- **Rollback** come first-class feature (supportato da Deployer)
- **Log consultabili** e scaricabili
- **Notifiche** (Slack/Teams/email) su success/fail

---

## Roadmap breve
1) Symfony 7.4: UI + DB + RBAC + CRUD progetti/ambienti  
2) Standard di recipe (es: base recipe comune + override per progetto)  
3) Scelta dell’architettura A o B:
   - **A:** integrazione API con CI (trigger + webhook log)
   - **B:** Messenger worker + runner isolato + Process + log streaming
4) Controlli: dry-run, preflight checks, policy ref/tag, approval gate  
5) Rollback e reportistica (tempi medi deploy, failure rate)

---

## Scelta
Architettura di riferimento: **A (Symfony orchestration → CI runner → Deployer)**.
