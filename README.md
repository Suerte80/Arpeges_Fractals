# Remerciments
https://forums.docker.com/t/setup-local-domain-and-ssl-for-php-apache-container/116015/2

# Commande a executé pour generer les scripts js
```bash
npm run build && npm run deploy
```

# Commande pour générer les certificats
openssl req -x509 -new -out mycert.crt -keyout mycert.key -days 365 -newkey rsa:4096 -sha256 -nodes

1 - add a line to your computer’s host file (/etc/hosts for Linux; C:\Windows\System32\drivers\etc\hosts for Windows) pointing the desired name to 127.0.0.1, i.e. adding this hostname to the line starting with 127.0.0.1

127.0.0.1  localhost local-docker