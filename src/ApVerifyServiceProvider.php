<?php

namespace LunarBuild\ApVerifyComponents;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ApVerifyServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// load service classes from Services directory
		$this->app->singleton(\LunarBuild\ApVerifyComponents\Services\GptZeroService::class);
	}
}
