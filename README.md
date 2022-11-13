
# Generate Design Pattern
### you can create your restful api easily by using this library


## Installation:
Require this package with composer using the following command:

```sh
composer require waad/repo-media
```

```sh
php artisan vendor:publish --provider=Waad\RepoMedia\PatternServiceProvider 
```

## Usage
##### in Model
##### $fillable = ['id',....];
##### This is where you will write  `sortable`, `filterable` columns 


##### in Model
##### $relations = ['category','post.user']; 
##### This is where you will write `relations` column 

##### in  `app\Providers\AppServiceProvider.php` file

```sh
public function boot()
    {
        $registrar = new \Waad\Repository\Helpers\Routing($this->app['router']);
        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });
    }
```

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

---

#### create validation 
```sh
php artisan repo:validation User
```


### Available command options:

Command | Description
--------- | -------
`--c` | Create Controller and linked with repository
`--m` | Create Model and linked with Controller Functions
`--model={ModelName}` | Insert model in controller if you have model
`--r` | Create apiResource Route in api.php
`--force` | override existing Repository





### License

Laravel Design Pattern Generator is free software licensed under the MIT license.
