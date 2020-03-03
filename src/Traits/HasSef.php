<?php

namespace TaffoVelikoff\LaravelSef\Traits;

use Cache;
use TaffoVelikoff\LaravelSef\Sef;

trait HasSef
{

	/**
     * Get the custom url (SEF)
     */
    public function sef() {
        return $this->morphOne(\TaffoVelikoff\LaravelSef\Sef::class, 'model');
    }

	/**
	* Save custom url
	*
	*/
	public function createSef($keyword) {
		$sef = Sef::create(['keyword' => $keyword]);
		$this->sef()->save($sef);
		$this->save();
	}

	/**
	* Update custom url
	*
	*/
	public function updateSef($keyword) {
		// Clear from cache
		Cache::forget('sef_'.$this->sefKeyword());
		
		// Update
		$this->sef->keyword = $keyword;
		$this->sef->save();
	}

	/**
	* Keyword
	*
	*/
	public function sefKeyword() {
		if($this->sef)
			return $this->sef->keyword;
		
		return null;
	}

	/**
	 * Full URL
	 */
	public function sefUrl() {
		return url($this->sef->keyword);
	}

}