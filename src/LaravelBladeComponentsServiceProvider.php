<?php

namespace Dcblogdev\LaravelBladeComponents;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class LaravelBladeComponentsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/components', 'ap-verify-components');

        Blade::component('ap-verify-components::tab', 'tab');
        Blade::component('ap-verify-components::modal', 'modal');
        Blade::component('ap-verify-components::form', 'form');
        Blade::component('ap-verify-components::dropdown-link', 'dropdown-link');
        Blade::component('ap-verify-components::dropdown', 'dropdown');
        Blade::component('ap-verify-components::button', 'button');
        Blade::component('ap-verify-components::2col', '2col');

        Blade::component('ap-verify-components::form.input', 'form.input');
        Blade::component('ap-verify-components::form.textarea', 'form.textarea');
        Blade::component('ap-verify-components::form.checkbox', 'form.checkbox');
        Blade::component('ap-verify-components::form.radio', 'form.radio');
        Blade::component('ap-verify-components::form.select', 'form.select');
        Blade::component('ap-verify-components::form.selectoption', 'form.selectoption');

        Blade::component('ap-verify-components::tabs.div', 'tabs.div');
        Blade::component('ap-verify-components::tabs.header', 'tabs.header');
        Blade::component('ap-verify-components::tabs.link', 'tabs.link');
    }
}
