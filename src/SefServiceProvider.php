<?php
	
namespace TaffoVelikoff\LaravelSef;

use Illuminate\Support\ServiceProvider;

class SefServiceProvider extends ServiceProvider {

	public function boot() {
		
		// Migrations
		$this->loadMigrationsFrom(__DIR__.'/../database/migrations');

		// Sef configuration
		$this->publishes([
			__DIR__.'/../config/sef.php' => config_path('sef.php'),
		], 'sef_config');
		
	}
}