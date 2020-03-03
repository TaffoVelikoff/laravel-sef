# TaffoVelikoff/LaravelSef
🔗 Search engine friendly urls for your Laravel website.

## Contents
[🤔  Why use it?](#why-use-it)

[💻 Requirements](#usage)

[⚙️ Installation](#installation)

[📚 Usage](#usage)

## Why use it?
There are many ways to create search engine friendly urls. For example you can use "slugs":

- https://mylaravel.com/pages/my-custom-url - SEF url for a page model
- https://mylaravel.com/products/my-custom-url - SEF url for a product model
- https://mylaravel.com/products/user-name - SEF url for a user model


What if you want to loose the prefix and have this instead:

- https://mylaravel.com/history - a page
- https://mylaravel.com/vans - a product
- https://mylaravel.com/john_doe - a user

This package will help you achieve that!

## Requirements

This package requires ***Laravel 5.8*** or above.

## Installation

You can install the package via composer:

```bash
composer require taffovelikoff/laravel-sef
```

Don't forget to run the migrations. There is a migration file for a "sefs" table, where all the custom urls will be stored.

```bash
php artisan migrate
```

## Usage

### 👉 STEP 1: Add the HasSef trait to a model
First, you need to add the TaffoVelikoff\LaravelSef\Traits\HasSef trait to your model.

```php
namespace App;

use TaffoVelikoff\LaravelSef\Traits\HasSef;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasSef;
}
```

### 👉 STEP 2: Create/update the SEF

```php
namespace App\Http\Controllers\Admin;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Create a product
    public function store() {

        // Validate the request and make sure the "sef" keyword is unique.

        // Create
        $prod = Product::create([
            'name'  => 'My product',
            'price' => 2500
        ]);

        // Model to be available on https://mylaravel.com/my_product
        $product->createSef('my_product');

        return redirect()->back();
    }

    // Update a product
    public function update($id) {
        // Get the product
        $product = Product::findOrFail($id);

        // Update to be available on https://mylaravel.com/my_new_url
        $product->updateSef('my_new_url');

        return redirect()->back();
    }
}

```

Now you can use the sefUrl() method to link the resource in your templates:

```html
<a href="{{ $product->sefUrl() }}">{{ $product->name }}</a>
```

### 👉 STEP 3: Call the right controller and method
You have a few options on how to call the controller and method used to view the model.

#### ▶️ Method 1: URL mappings in config file.
Publish the configuration file:

```bash
php artisan vendor:publish --tag:sef_config
```

Add this to your routes file (typically web.php) at the ***VERY BOTTOM***.

```php
Route::get('{keyword}', '\TaffoVelikoff\LaravelSef\Http\Controllers\SefController@viaConfig');
```

Say you are trying to reach https://mylaravel.com/something. If /something is not defined in your app routes SefController@viaConfig will be called
.
This method will search in the "sefs" table for a record with keyword = 'something'. If no such record exists a 404 error will be thrown.
If the record exists the method will check if the owner model type (class) exists in the routes array in config/sef.php.

```php
// config/sef.php

return [

    'routes' => [
        'App\Product' => [ // The owner model type
            'controller' => 'App\Http\Controllers\ProductController', // controller, that handles the request
            'method' => 'index' // the method to view (show) the model
        ],
    ]

];
```

#### ▶️ Method 2: Define a $sef_method property in the model
Add this to your routes file (typically web.php) at the ***VERY BOTTOM***.

```php
Route::get('{keyword}', '\TaffoVelikoff\LaravelSef\Http\Controllers\SefController@viaMethod');
```

Say you are trying to reach https://mylaravel.com/something. If /something is not defined in your app routes SefController@viaMethod will be called
.
This method will search in the "sefs" table for a record with keyword = 'something'. If no such record exists a 404 error will be thrown.
If the record exists the method will next check what is the owner model type. Say the owner model is of type "App\Product". Next the method will check for a public static property $sef_method in the App\Product model:

```php
namespace App;

use TaffoVelikoff\LaravelSef\Traits\HasSef;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasSef;

    // Controller@method to view/show the model.
    public static $sef_method = 'App\Http\Controllers\ProductController@index';
}

```

In this example "App\Http\Controllers\ProductController@index" is the controller and method used to view/show the model.

```php
namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show the model
    public function index($id) {
        // Get the product
        $prod = Product::findOrFail($id);

        // Display template
        return view('product');
    }
}

```

#### ▶️ Method 3: Your own controller
Add this to your routes file (typically web.php) at the ***VERY BOTTOM***.

```php
Route::get('{keyword}', 'App\MySefController@redirect');
```

Create your own controller:

```php
namespace App\Http\Controllers;

use TaffoVelikoff\LaravelSef\Sef;
use App\Http\Controllers\Controller;

class SefController extends Controller
{

    // Redirect to right controller and method via the mapping in config
    public function redirect($keyword) {

        // Find SEF with keyword
        $sef = Sef::where('keyword', $request->route()->parameters['keyword'])->first();

        // Add your own code
        //return app()->call(..., ['id' => $sef->model_id]);
    }
}
```

## License
This package is open-sourced software licensed under the [MIT](https://choosealicense.com/licenses/mit/) license.