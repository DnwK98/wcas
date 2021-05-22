# WCAS
Website create and share

Application for building, storing and serving simple web pages

### Run app local


```bash
# Build an application image
./docker/build.sh

# Create .env.docker from .env.dist and modify for your needs
cp .env.dsit .env.docker
vi .env.docker

# Run application
./docker/run.sh

# Stop application
./docker/stop.sh
```


### Test application
Run tests in container
```bash
./docker/test.sh
```
To run tests in local env you have to first set up all dependencies, 
create `.env.test.local` file, override all required settings and then run
`composer test:all` to run all tests. Local testing requires MySql database

### Generating JWT key

```bash
php bin/console lexik:jwt:generate-keypair

// Private base64 encoded value to insert in .env
cat config/jwt/private.pem | base64 | tr -d '\n'
```
