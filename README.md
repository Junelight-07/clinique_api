Projet API Symfony de
Alexy DE BARROS et
Olivier DELMAS

Afin de lancer le projet, il faut suivre les étapes suivantes :

docker compose up
symfony server:start

Lors de la première utilisation, il faut pouvoir créer le premier utilisateur directeur qui lui, pourra créer des users.
Pour cela, il faut dans un premier temps décommenter la ligne 33 du fichier src/Entity/User.php et commenter de la ligne 27 à 31 du même fichier.

```php
// lignes à commenter
 new Post(
            security: "is_granted('ROLE_DIRECTOR')",
            securityMessage: "Seul le directeur peut créer du personnel.",
            processor: UserPasswordHasherProcessor::class
        ),
//    ligne à décommenter
//        new Post(processor: UserPasswordHasherProcessor::class),
```

Il faut également décommenter la ligne 51 du fichier src/config/packages/security.yaml.

```yaml
# ligne à décommenter
# - { path: ^/api/users, roles: PUBLIC_ACCESS }
 ```
Il faut maintenant générer les token jwt à l'aide de la commande suivante :

```bash
php bin/console lexik:jwt:generate-keypair
```
Si vous rencontrez une erreur OpenSSL sur Windows, il faut l'installer, idéalement avec Chocolatey dans le terminal :

```bash
choco install openssl
```

Puis redémarrer votre terminal et réessayer.

Lors de requête PostMan, il faut ajouter dans Headers un Content-Type avec application/ld+json.

Par la suite, il faut se rendre sur la route https://127.0.0.1:8000/api/users et envoyer une requête POST avec les informations suivantes pour créer un directeur qui lui, peut créer du personnel :
{
  "email": "example@example.com",
  "plainPassword": "yourpassword",
  "firstName": "John",
  "lastName": "Doe",
  "roles": ["ROLE_DIRECTOR"]
}

Lors de requêtes PostMan, il faut ajouter une autorisation tel que Bearer Token puis, insérer votre token.