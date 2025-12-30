# DRAVEN Deploy Control Plane — RBAC – Controllo degli accessi basato sui ruoli

**RBAC** (*Role-Based Access Control*) è il modello con cui si gestisce chi può fare cosa assegnando permessi a ruoli e ruoli agli utenti.

---

## Cos’è (concetto)
RBAC definisce:

- **Ruoli**: insiemi di permessi coerenti con un compito (es. *Admin*, *Operatore*, *Viewer*)
- **Permessi**: azioni consentite (es. *leggere log*, *modificare configurazioni*, *avviare deploy*)
- **Utenti/Service account**: a cui sono assegnati uno o più ruoli

La gestione avviene tramite ruoli invece che tramite permessi puntuali, semplificando onboarding, cambio mansione e revoca accessi.

---

## Esempio pratico
Esempio con tre ruoli:

- **Admin**: può gestire tutto (utenti, configurazioni, deploy, segreti)
- **Operatore**: può fare deploy e vedere metriche/log, ma non gestire utenti o segreti
- **Viewer**: può solo leggere (dashboard, log, report)

Se cambia la mansione di una persona, basta modificare il ruolo assegnato senza riassegnare i permessi singolarmente.

---

## Perché si usa
- **Least privilege**: assegna solo i permessi necessari al ruolo
- **Auditabilità**: semplifica la risposta a “chi ha accesso a cosa?”
- **Scalabilità organizzativa**: funziona con molti utenti e sistemi
- **Riduzione errori**: riduce permessi assegnati manualmente e incoerenze

---

## Limiti tipici
- **Role explosion**: granularità eccessiva può produrre un numero elevato di ruoli
- **Regole dinamiche**: RBAC puro è meno adatto quando l’accesso dipende da contesto (orario, device, geolocalizzazione) o attributi molto variabili

---

## Alternative / modelli vicini
- **ABAC (Attribute-Based Access Control)**: decide in base ad attributi (utente, risorsa, contesto) e policy; più flessibile ma più complesso
- **ACL (Access Control List)**: permessi legati direttamente alla risorsa (chi può accedere a *quel* file/oggetto)
- **ReBAC (Relationship-Based Access Control)**: accesso basato sulle relazioni (es. “sono nel team X”, “seguo quel progetto”, “sono owner di quella risorsa”)

---

## Dove ricorre spesso
- Sistemi enterprise (IAM), applicazioni con permessi per funzione
- Ambienti cloud: per esempio **Azure RBAC**
- Cluster/container: per esempio **Kubernetes RBAC**

---

## Fonti (per verifica)
```text
https://csrc.nist.gov/glossary/term/role_based_access_control
https://www.ibm.com/think/topics/rbac
https://learn.microsoft.com/en-us/azure/role-based-access-control/overview
https://docs.redhat.com/en/documentation/jboss_enterprise_application_platform_common_criteria_certification/6.2.2/html/security_guide/enabling_role-based_access_control
https://en.wikipedia.org/wiki/Attribute-based_access_control
```
