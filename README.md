# Laravel HMVC Project

This Laravel project follows the Hierarchical Model-View-Controller (HMVC) architecture, providing a modular structure for easier maintenance and scalability.

## Project Structure

laravel_hmvc_project/
|-- app/
|-- bootstrap/
|-- config/
|-- database/
|-- modules/
|   |-- Module1/
|   |   |-- Controllers/
|   |   |-- Models/
|   |   |-- Views/
|   |   |-- ...
|   |
|   |-- Module2/
|   |   |-- Controllers/
|   |   |-- Models/
|   |   |-- Views/
|   |   |-- ...
|   |
|   |-- ...
|
|-- public/
|-- resources/
|-- routes/
|-- ...

## Installation
  git clone https://github.com/your-username/your-repository.git
  composer install
  php artisan key:generate
  php artisan migrate
  php artisan serve

## TO work on new module use below commands  
  php artisan make:module ModuleName
  php artisan module:make posts
  php artisan module:make Blog --api (for api module)

## Migration and seed Module
  php artisan module:make-migration create_posts_table Blog
  php artisan module:make-seed seed_fake_blog_posts Blog
  php artisan module:make-controller PostsController Blog  


## Flags
    By default when you create a new module, the command will add some resources like a controller, seed class, service provider, etc. automatically. If you don’t want these, you can add --plain flag, to generate a plain module.

    php artisan module:make Blog --plain
or

    php artisan module:make Blog -p
## Additional flags are as follows:

    Generate an api module.

    php artisan module:make Blog --api
## Do not enable the module at creation.

    php artisan module:make Blog --disabled
or

    php artisan module:make Blog -d


## Useful Tip:
    You can use the following commands with the --help suffix to find its arguments and options.

    Note all the following commands use “Blog” as example module name, and example class/file names

## Utility commands
    module:make

## Generate a new module.

    php artisan module:make Blog
    module:make

## Generate multiple modules at once.

    php artisan module:make Blog User Auth
    module:use

## Use a given module. This allows you to not specify the module name on other commands requiring the module name as an argument.

    php artisan module:use Blog
    module:unuse

## This unsets the specified module that was set with the module:use command.

    php artisan module:unuse
    module:list

## List all available modules.

    php artisan module:list
    module:migrate

## Migrate the given module, or without a module an argument, migrate all modules.

    php artisan module:migrate Blog
    module:migrate-rollback

## Rollback the given module, or without an argument, rollback all modules.

    php artisan module:migrate-rollback Blog
    module:migrate-refresh

## Refresh the migration for the given module, or without a specified module refresh all modules migrations.

    php artisan module:migrate-refresh Blog
    module:migrate-reset Blog

## Reset the migration for the given module, or without a specified module reset all modules migrations.

    php artisan module:migrate-reset Blog
    module:seed

## Seed the given module, or without an argument, seed all modules

    php artisan module:seed Blog
    module:publish-migration

## Publish the migration files for the given module, or without an argument publish all modules migrations.

    php artisan module:publish-migration Blog
    module:publish-config

## Publish the given module configuration files, or without an argument publish all modules configuration files.

    php artisan module:publish-config Blog
    module:publish-translation

## Publish the translation files for the given module, or without a specified module publish all modules migrations.

    php artisan module:publish-translation Blog
    module:enable

## Enable the given module.

  php artisan module:enable Blog

  module:disable

## Disable the given module.
    php artisan module:disable Blog
    module:update

## Update the given module.

    php artisan module:update Blog

## Generator commands
    module:make-command

## Generate the given console command for the specified module.

    php artisan module:make-command CreatePostCommand Blog
    module:make-migration

## Generate a migration for specified module.

    php artisan module:make-migration create_posts_table Blog
    module:make-seed

## Generate the given seed name for the specified module.

    php artisan module:make-seed seed_fake_blog_posts Blog
    module:make-controller

## Generate a controller for the specified module.

    php artisan module:make-controller PostsController Blog

## Optional options: --plain,-p : create a plain controller --api : create a resouce controller
    module:make-model

## Generate the given model for the specified module.

    php artisan module:make-model Post Blog

## Optional options: --fillable=field1,field2: set the fillable fields on the generated model
--migration, -m: create the migration file for the given model

    module:make-provider

## Generate the given service provider name for the specified module.

    php artisan module:make-provider BlogServiceProvider Blog
    module:make-middleware

## Generate the given middleware name for the specified module.

    php artisan module:make-middleware CanReadPostsMiddleware Blog
    module:make-mail

## Generate the given mail class for the specified module.
    php artisan module:make-mail SendWeeklyPostsEmail Blog
    module:make-notification

## Generate the given notification class name for the specified module.
    php artisan module:make-notification NotifyAdminOfNewComment Blog
    module:make-listener

## Generate the given listener for the specified module. Optionally you can specify which event class it should listen to. It also accepts a --queued flag allowed queued event listeners.

    php artisan module:make-listener NotifyUsersOfANewPost Blog
    php artisan module:make-listener NotifyUsersOfANewPost Blog --event=PostWasCreated
    php artisan module:make-listener NotifyUsersOfANewPost Blog --event=PostWasCreated --queued
    module:make-request

## Generate the given request for the specified module.

    php artisan module:make-request CreatePostRequest Blog
    module:make-event

## Generate the given event for the specified module.

    php artisan module:make-event BlogPostWasUpdated Blog
    module:make-job

## Generate the given job for the specified module.

    php artisan module:make-job JobName Blog
    php artisan module:make-job JobName Blog --sync # A synchronous job class
    module:route-provider

## Generate the given route service provider for the specified module.

    php artisan module:route-provider Blog
    module:make-factory

## Generate the given database factory for the specified module.

    php artisan module:make-factory ModelName Blog
    module:make-policy

## Generate the given policy class for the specified module.

    The Policies is not generated by default when creating a new module. Change the value of paths.generator.policies in modules.php to your desired location.

    php artisan module:make-policy PolicyName Blog
    module:make-rule

## Generate the given validation rule class for the specified module.

    The Rules folder is not generated by default when creating a new module. Change the value of paths.generator.rules in modules.php to your desired location.

    php artisan module:make-rule ValidationRule Blog
    module:make-resource

## Generate the given resource class for the specified module. It can have an optional --collection argument to generate a resource collection.

    The Transformers folder is not generated by default when creating a new module. Change the value of paths.generator.resource in modules.php to your desired location.

    php artisan module:make-resource PostResource Blog
    php artisan module:make-resource PostResource Blog --collection
    module:make-test

## Generate the given test class for the specified module.

    php artisan module:make-test EloquentPostRepositoryTest Blog

## Basic Application
    Syntax of create module command:

    php artisan make:module module_name
    Then run the following command to create module let’s do an example for Posts module.

    php artisan make:module posts

## After running above commands it will generate our Posts module under Modules folder. See below Laravel module structures:

    app/
    bootstrap/
    vendor/
    Modules/
    ├── Posts/
        ├── Assets/
        ├── Config/
        ├── Console/
        ├── Database/
            ├── Migrations/
            ├── Seeders/
        ├── Entities/
        ├── Http/
            ├── Controllers/
            ├── Middleware/
            ├── Requests/
        ├── Providers/
            ├── PostsServiceProvider.php
            ├── RouteServiceProvider.php
        ├── Resources/
            ├── assets/
                ├── js/
                    ├── app.js
                ├── sass/
                    ├── app.scss
            ├── lang/
            ├── views/
        ├── Routes/
            ├── api.php
            ├── web.php
        ├── Repositories/
        ├── Tests/
        ├── composer.json
        ├── module.json
        ├── package.json
        ├── webpack.mix.js
## Now, we successfully generated our Posts module. Let's test it by running the command below:

php artisan serve


## Run the following command for Setting up Project

    ```composer install
    php artisan key:generate
    php artisan storage:link
    php artisan migrate
    php artisan db:seed
    
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear
    php artisan config:cache
    php artisan view:cache
    php artisan route:cache
    composer dump-autoload```




