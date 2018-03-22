# Trip Builder
Richard Morgan <r_morgan@sympatico.ca>

Test application based on Laravel Lumen 5.4, running on docker containers.

The Response is alway json, in the [json-api](http://jsonapi.org/) format.

## Dev Environment
The dev environment is docker based. To run the php app, nginx, postgres database, and redis cache containers.

### launch
simply run docker-compose up


```bash
docker-compose up
```

This will download the required base images, then build the additional tools into new images, and then launch new containers.
A container also runs briefly to generate the api docs.

### First run
The zip file contains all the necessary files. If you cloned the git repo, you will need to run composer to 
fetch third party packages.

```bash
docker-compose up composer install
```
### Setup .env file
Copy the .env.example to a new file called .env
```bash
cp .env.example .env
```


### Setup database
Before testing the app, you will need to prepare the database.
Database migrations and seeds are provided for testing the app. These can be run in docker with the following command:

```bash
docker-compose exec app php artisan migrate:refresh --seed
```

### testing
Some tests are provided, powered by [codeception](http://codeception.com/). 

```bash
docker-compose exec app vendor/bin/codecept run
```

## Api Documentation
Api documentation is automatically generated using [apidocjs](http://apidocjs.com) within a container.
The docker containers expose the api documentation on [http://localhost:8080/docs/](http://localhost:8080/docs/)

As well as describing each api endpoint, the docs include forms for testing the api.

You can regenerate the docs with the following command

```bash
docker-compose up apidocs
```

## Testing online
The api is available online at [https://tripbuilder.xai-corp.net](https://tripbuilder.xai-corp.net).
And the docs are available at [https://tripbuilder.xai-corp.net/docs/](https://tripbuilder.xai-corp.net)
