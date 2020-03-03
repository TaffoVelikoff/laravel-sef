<?php 

/**
|
| URL mapping for the SefController.
| If you want to use this you need to add this route in web.php:
| Route::get('{keyword}', '\TaffoVelikoff\LaravelSef\Http\Controllers\SefController@viaConfig');
| Make sure this route is at the bottom of the file.
|
| Read more on how to use here: https://github.com/TaffoVelikoff/laravel-sef
|
*/

return [

	'routes' => [

		// Example Bellow
		'App\Product' => [ // The owner model type
			'controller' => 'App\Http\Controllers\ProductController', // the controller, that handles the request
			'method' => 'index'	// the method to view (show) the model
		],

		// 'App\Page' => [
		//		'controller' => 'App\Http\Controllers\PageController',
		//		'method' => 'index'
		// ],

	]

];