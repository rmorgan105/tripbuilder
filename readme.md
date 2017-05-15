# Trip Builder
- Richard Morgan <r_morgan@sympatico.ca>

Test application based on Laravel Lumen 5.4, running on docker containers.

## Launch Environment
The dev environment is docker based. To run the php app, nginx, postgres database, and redis cache containers, simply run docker-compose up


```bash
docker-compose up
```

This will download the required base images, then build the additional tools into new images, and then launch new containers.
A container also runs briefly to generate the api docs.

## Setup database
Database migrations and seeds are provided for testing the app. These can be run in docker with the following command:

```bash
docker-compose exec app php artisan migrate:refresh --seed
```


## Api Documentation
The docker containers expose the api documentation on [http://localhost:8080/docs/](http://localhost:8080/docs/)


## 
