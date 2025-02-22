# Auction lots
Take home assignment for WizAds made using Laravel, Vue 3, Typescript and Bootstrap.

## Project structure
It is pretty simple:

* `api/` - Laravel powered API with SQLite as a database
* `app/` - SPA application in Vue 3, Typescript, Bootstrap

## Run with Docker
You will need `docker compose` and `docker` to run the application.

You can use `docker-compose` to run the application:
```sh
docker-compose up -d
```

To stop application you can use:
```sh
docker-compose down
``` 

### Notes
1. __The images are not ready for production__, I made them just to be able to run the application.

2. The SQLite __database data is not persisted between runs__, but this can be fixed by adding volume in `docker-compose.yaml` file.

## Technical details and implementation
While designing the application I assumed that:
* items in a tree can be moved only within their parent, i.e. they cannot be moved out of parent (however this can be implemented with the current design)
* to store relations I decided to go with JSON column as this allows me to store both relations and order of children (but I know what are foreign keys and how to create relations in SQL)
* when deleting the parent, his children are not deleted, meaning that the orphans will be there in a database. This can be solved using either materialized path (i.e. storing the item path in format `{parentId}/{otherParentId}/{itemId}`) or creating a background task that will prune the orphans from time to time
* authentication is not really reactive in a frontend, meaning you will need to refresh the page so the access token can be applied to the subsequent requests to the server
* there are more things that makes it not production ready but I guess it is not the point of the task to make it as close to real product as possible
