# Laravel Design Pattern Generator (api generator)
### you can create your restful api easily by using this library
##### and you can filter, sort and include eloquent relations based on a request


## Installation:
Require this package with composer using the following command:

```sh
$ composer require waad/repo-media
```

```sh
$ php artisan vendor:publish --provider=Waad\RepoMedia\PatternServiceProvider 
```

## Usage
##### in folder `config` You will find `jsonapi.php`
##### This is where you will write `relations`, `sortable` columns and `filterable` columns 

## Commands:
##### full command
```sh
php artisan repo:model User --c --r --m
```
##### or if you have model 
```sh
php artisan repo:model User --c --r --model=User
```
##### and you can use `--force` command


### Available command options:

Command | Description
--------- | -------
`--c` | Create Controller and linked with repository
`--m` | Create Model and linked with Controller Functions
`--model={ModelName}` | Insert model in controller if you have model
`--r` | Create apiResource Route in api.php
`--force` | override existing Repository



##### FILTER A QUERY BASED ON A REQUEST
```sh
/users?filter[name]=John
```

INCLUDING RELATIONS BASED ON A REQUEST
```sh
/users?include=posts
/users?include=posts,comments
```

SORTING A QUERY BASED ON A REQUEST
```sh
/users?sort=id
/users?sort=-id
```


TAKE DATA
```sh
/users?take=10
```


SKIP DATA
```sh
/users?skip=10
```

CUSTOM CONDITIONS
```sh
/users?where={"column":"parent_to","condition":"!=", "value":"0"}
```


### License

Laravel Design Pattern Generator is free software licensed under the MIT license.
