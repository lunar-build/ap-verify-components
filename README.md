# AP Verify components

This repository contains:

- [x] a collection of Laravel Blade components for reuse across the toolbox and verify projects
- [x] services for API integration with third-party services such as GPTZero

## Installation in AP projects

Projects will sync to this repository via Composer specifically pulling the `main` branch.

### Add repository to composer.json

```json
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/lunar-build/ap-verify-components"
	}
],
```

### Install via Composer

```bash
composer require lunar-build/ap-verify-components:dev-main
```

### Updates

To update to the latest version, run:

```bash
composer update lunar-build/ap-verify-components
```

## Usage

### Blade components

```html
<x-ap-pie-chart
	:value="$result->sentence_average_score * 100"
	caption="Average score for all sentences"
>
	{{ $percentage }}
</x-ap-pie-chart>
```

The [LaravelBladeComponentsServiceProvider.php](src/LaravelBladeComponentsServiceProvider.php#L14) file registers the components with the `ap` namespace.

#### Sass import

To use the component styles ensure the project's `vite.config.js` file has an alias for this package in the vendor folder:

You only need to do this once:

```js
resolve: {
	alias: {
		$apComponents: resolve(
			"/vendor/lunar-build/ap-verify-components/src/components"
		),
	},
},
```

then in your main Sass file import the styles:

```scss
@import '$apComponents/pie-chart/pie-chart';
```

### Services

You can use the services provided in this package by including the relevant class via its namespace. For example, to use the GPTZero service:

```php
use LunarBuild\ApVerifyComponents\Services\GptZeroService;

GptZeroService::detectGenAI($batch);
```
