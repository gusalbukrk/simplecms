## Run

- `./run.sh <password>` = **start** app
  - script takes 1 argument — MySQL root user password
- `docker compose down` = **stop** app

## Deploy

### Register a domain

- register a domain on [**Namecheap**](https://www.namecheap.com/)

### Set up hosting

- create an account on [**DigitalOcean**](https://www.digitalocean.com/)
- create a **project** and a **SSH key** on [settings](https://cloud.digitalocean.com/account/security)
- create a **droplet** inside the project created previously and select the SSH key created on the previous step as the authentication method
- update **nameservers** on Namecheap to point to DigitalOcean as the DNS provider ([instructions](https://docs.digitalocean.com/tutorials/dns-registrars/))
  - NOTE: later you will revert to use Namecheap but for now using DigitalOcean is necessary to easily issue a SSL certificate

### Initial server configuration

- access the droplet using **SSH** — example: `ssh root@137.184.12.128`
- [create user and give they root privileges](https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu-22-04)
- [install docker on your server](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-22-04)
- [install docker compose on your server](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-22-04)

### Issue Let's Encrypt certificate

- [enable **SSL** using Let's Encrpyt](https://www.digitalocean.com/community/tutorials/how-to-acquire-a-let-s-encrypt-certificate-using-dns-validation-with-certbot-dns-digitalocean-on-ubuntu-20-04)
  - NOTE: at section 'Step 3 - Issuing a Certificate', instead of using one of the commands provided as example, use instead ([source](https://certbot-dns-digitalocean.readthedocs.io/en/stable/)): `sudo certbot certonly --dns-digitalocean --dns-digitalocean-credentials ~/certbot-creds.ini -d simpletables.xyz -d '*.simpletables.xyz'` to acquire a **wildcard certificate**
  - NOTE: during this step you'll need to create a **DigitalOcean token** ([instructions](https://docs.digitalocean.com/reference/api/create-personal-access-token/)]
  - NOTE: DigitalOcean must be the DNS provider because we're using the certbot-dns-digitalocean plugin, if using any other provider you will get the error [**Unable to determine base domain** for simpletables.xyz](https://community.letsencrypt.org/t/unable-to-determine-base-domain-for-using-names-domain-com-com/94306)

### Set up a free email

- until now we used DigitalOcean as the **DNS provider**, however Namecheap offers functionalities DigitalOcean doesn't because of that we'll switch to Namecheap (NOTE: you'll need to switch back to DigitalOcean when it's time to renew the SSL certificate)
  - on DigitalOcean, delete domain
  - on Namecheap, remove DigitalOcean's nameservers by choosing 'Namecheap BasicDNS' and adding the following records:
    - type: A record, host: @, value: droplet ip
    - type: A record, host: *, value: droplet ip
- configure Namecheap to **forward emails** sent to a custom domain address (e.g. `admin@simpletables.xyz`) to a regular email (e.g. `simpletables@outlook.com`) — at 'REDIRECT EMAIL' section add the following record ([source](https://www.namecheap.com/support/knowledgebase/article.aspx/308/2214/how-to-set-up-free-email-forwarding/)):
  - alias: admin, forwards to: simpletables@outlook.com
- to be able to **send emails** with your custom domain, you must set up a SMTP server — sign up to [SMTP2GO](https://www.smtp2go.com/) with a custom domain email and follow given instructions
