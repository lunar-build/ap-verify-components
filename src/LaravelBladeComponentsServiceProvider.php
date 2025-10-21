<?php

namespace LunarBuild\ApVerifyComponents;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class LaravelBladeComponentsServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->loadViewsFrom(__DIR__ . '/components/**/*', 'ap');

		Blade::component('ap::pie-chart', 'pie-chart', 'ap');
	}
}
