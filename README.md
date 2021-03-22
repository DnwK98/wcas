### Generating JWT key

```bash
php bin/console lexik:jwt:generate-keypair

// Private base64 encoded value to insert in .env
cat config/jwt/private.pem | base64 | tr -d '\n'
```