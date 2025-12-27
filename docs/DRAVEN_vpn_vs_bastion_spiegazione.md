“VPN” e “bastion” (o *bastion host / jump host*) sono due modalità—spesso complementari—per permettere accessi **sicuri** a una rete o a server non esposti direttamente su Internet.

## VPN (Virtual Private Network)
Una VPN crea un **tunnel cifrato** tra un client e una rete remota (es. una VPC/VLAN in cloud o la rete di una VPS). Dopo la connessione:

- il client entra nella rete remota come se fosse locale
- sono raggiungibili risorse **private**: database, Redis, pannelli admin, server interni
- la rete remota vede il traffico come proveniente da un IP interno VPN
- l’accesso è controllato con utenti/certificati e policy

**Esempio:** collegamento VPN seguito da `ssh 10.0.1.10` o apertura di `http://10.0.2.20:8080`, non esposti pubblicamente.

**Vantaggi:** accesso di rete ampio e continuativo a più servizi.  
**Considerazioni:** se un endpoint o le credenziali VPN vengono compromessi, l’accesso interno è immediato; servono controlli come MFA, split tunnel e ACL.

---

## Bastion (Bastion host / Jump host)
Un bastion è un server esposto (in modo controllato) su Internet, pensato per fare da ponte verso macchine interne. Tipicamente:

- è esposto su Internet solo il bastion (di solito SSH 22, o RDP in contesti Windows)
- gli altri server restano **privati**, accessibili solo dalla rete interna
- ci si collega al bastion e da lì si effettua il “salto” sugli host interni (*jump*)

**Esempio:**
- `ssh user@bastion.public.ip`
- poi `ssh user@server-interno` oppure uso di `ProxyJump` da un solo comando

**Vantaggi:** superficie esposta ridotta, logging centralizzato, regole firewall semplificate.  
**Considerazioni:** è un punto unico da hardenizzare; non fornisce automaticamente accesso “di rete” completo come una VPN ed è orientato a SSH/jump (con possibilità di port forwarding).

---

## Scenari d’uso
- **VPN**: accesso a molti servizi interni come in LAN (DB, UI interne, microservizi, monitoring) con visibilità di rete.
- **Bastion**: accesso agli host interni tramite un punto controllato, spesso per SSH/administration, evitando l’accesso di rete completo.
- **Entrambi**: combinazione frequente in cloud/aziende; VPN per entrare nella rete, bastion come punto di salto/controllo o alternativa quando la VPN non è disponibile.

---

## Nota per il contesto deploy/Deployer
In un setup con più progetti e ambienti:
- il **bastion** permette a Deployer/CI di collegarsi a un solo endpoint pubblico per poi raggiungere i server interni;
- la **VPN** è tipica per accessi interattivi (dev/ops) o strumenti che devono parlare con DB o servizi interni.
