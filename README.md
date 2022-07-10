
# PHP MVC Framework

This is a simple MVC framework for building web applications in PHP. It's free and open-source.

## Setup application using this framework

 1. First, download the framework, either directly or by cloning the repository.
 1. Run `composer install` to install the project dependencies.
 1. Configure your web server to have the public folder as the web root.
 1. Create routes, add controllers, views and models.
## Routing

Routes are added with the add method. You can add fixed URL routes, and specify the controller and action, like this:
```php
$app->router->get('/', [Controller::class, 'action']);
```

Or you can add route without controller and return view, like this:

```php
$app->router->get('/', 'view');
```
## Controllers

Controllers respond to user actions (clicking on a link, submitting a form etc.). Controllers are classes that extend the `App\Http\Controllers\Controller` class.

Controllers are stored in the `App\Http\Controllers` folder. A sample index controller and login controller included. Controller classes need to be in the `App\Http\Controllers` namespace. You can add subdirectories to organise your controllers, so when adding a route for these controllers you need to specify the namespace.
## Views

Views are used to display information (normally HTML). View files go in the templates folder. Views can be in one format: .blade.php. No database access or anything like that should occur in a view file. You can render a standard blade.php view in a controller, optionally passing in variables, like this:

```php
use Core\View;

new View('index', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```
## Models

Models are used to get and store data in your application. They know nothing about how this data is to be presented in the views. Models extend the `Core\Concerns\Model` class and use PDO to access the database. They're stored in the `App/Models` folder. A sample user model class is included in `App/Models/User.php`

### Model Methods

#### Get

```php
User::get();
```

#### Where

```php
User::where('name', 'John')->get();
```

#### First

```php
User::where('name', 'John')->first();
```

#### Update

```php
User::where('name', 'John')->update($data);
```

or

```php
User::where('name', 'John')->first()->update($data);
```

#### Create

```php
User::create($data);
```
## Validation

Validator are used to validate request in controllers. You need to pass data to validator, like this:

```php
use Core\Validator;

Validator::make($data, $rules, $messages, $attributes)->validate();
```
You can also use controller function `validate()`. A simple validation is included in `App/Http/Controllers/Auth/LoginController.php`

## Errors

After validation you can get errors with `Core\Error\Error` class, like this:
```php
use Core\Error\Error;

Error::get($field);
```

#### There are other methods

##### All
```php
Error::all(); //return all error
```

##### Has
```php
Error::has($field); //return boolean
```

##### first
```php
Error::first($field); //return first eror for field
```

##### last
```php
Error::last($field); //return last eror for field
```

Also you can use `$errors` variable in views. A simple usage is included in `templates/auth/login.blade.php`