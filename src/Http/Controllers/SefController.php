<?php

namespace TaffoVelikoff\LaravelSef\Http\Controllers;

use Illuminate\Http\Request;
use TaffoVelikoff\LaravelSef\Sef;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

class SefController extends Controller
{

	// Redirect to right controller and method via the mapping in config
	public function viaConfig(Request $request, $keyword = null) {

		// Find SEF with keyword
		$sef = Sef::where('keyword', $request->route()->parameters['keyword'])->first();

		// Check if url exists
		if(!$sef) abort(404);

		// Get ID and model type of owner
		$id = $sef->model_id;
		$model = $sef->model_type;

		// Get sef mapping
		$sefModels = config('sef');

		// Redirect
		if(array_key_exists($model, $sefModels)) {
			return app()->call($sefModels[$model]['controller'].'@'.$sefModels[$model]['method'], ['id' => $id]);
		}

		// Abort if mapping not found
		abort(404);
	}

	// Redirect to right controller and method via model property
	public function viaProperty(Request $request, $keyword = null) {

		// Find SEF with keyword
		$sef = Sef::where('keyword', $request->route()->parameters['keyword'])->first();

		// Check if url exists
		if(!$sef) abort(404);

		// Check if model has sef_method property
		if(!isset($sef->model_type::$sef_method))
			abort(404);

		// Redirect
		return app()->call($sef->model_type::$sef_method, ['id' => $sef->model_id]);
		
	}

}