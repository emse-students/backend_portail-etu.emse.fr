API of portail-etu.emse.fr
===============

This application is the backend of the main website of [portail-etu.emse.fr](https://portail-etu.emse.fr).
It handle the database of the Student Union of the École des Mines de Saint-Étienne

Installation
------------

### 1. Clone the project : 
``` bash
$ git clone https://github.com/CorentinDoue/backend_portail-etu.emse.fr.git
```

### 2. Install the dependencies :
``` bash    
$ composer install
```

### 3. Generate the SSH keys for the LexikJWTAuthenticationBundle:
``` bash
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```query {
  users {
    id
    firstname
    lastname
    type
    promo
  }
}

mutation {
  createUser (
    firstname: "Théophane",
    lastname: "Tassy",
    type: "ICM",
    promo: 2016
  ) {
    id
    firstname
    lastname
    type
    promo
  }
}

In case first ```openssl``` command forces you to input password use following to get the private key decrypted
``` bash
$ openssl rsa -in config/jwt/private.pem -out config/jwt/private2.pem
$ mv config/jwt/private.pem config/jwt/private.pem-back
$ mv config/jwt/private2.pem config/jwt/private.pem
```

### 4. Update `.env` with your local information :

- `DATABASE_URL` : Your local database
- `CORS_ALLOW_ORIGIN` : If you want your API to be available from outside your server
- `APP_ENV` : `dev` while you are developing, `prod` for production mode
- `APP_SECRET` and `JWT_PASSPHRASE` : to be more secured you can change them
