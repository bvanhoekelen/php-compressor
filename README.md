

# PHP compressor
[![Hex.pm](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000&style=flat-square)](https://github.com/bvanhoekelen/php-compressor/blob/master/LICENSE)
[![Packagist Prerelease](https://img.shields.io/packagist/vpre/bvanhoekelen/php-compressor.svg?maxAge=2592000&style=flat-square)](https://packagist.org/packages/bvanhoekelen/php-compressor)
[![Packagist](https://img.shields.io/packagist/dt/bvanhoekelen/php-compressor.svg?maxAge=2592000&style=flat-square)](https://packagist.org/packages/bvanhoekelen/php-compressor)
[![Github All Releases](https://img.shields.io/github/downloads/bvanhoekelen/php-compressor/totlal.svg?maxAge=2592000&style=flat-square)](https://github.com/bvanhoekelen/php-compressor)
[![Github issues](https://img.shields.io/github/issues/bvanhoekelen/php-compressor.svg?maxAge=2592000&style=flat-square)](https://github.com/bvanhoekelen/php-compressor/issues)
[![GitHub release](https://img.shields.io/github/release/bvanhoekelen/php-compressor.svg?maxAge=2592000&style=flat-square)](https://github.com/bvanhoekelen/php-compressor)

<p align="center"><img src="/assets/banner.png" alt="php-compressor" /></p>

## Highlight
- Merge several files into one file
- Live compiler, fast and no additional program is needed
- The ability to set variables » [see variable in help](/docs/home.md)
- Support .css, .fcss and .js
- Required no changes on the live server
- Reduced server load and client load time of each page
- Optimizing assets for higher rank in google search
- Easy to install » [Install guide](#Instalation)

## Workflow

### Input
```
Root/
└── Resources/
    └── css/
    │   ├── table.css
    │   ├── alert.css
    │   ├── button.css
    │   ...
    └── js/
    │   ├── table.css
    │   ├── alert.css
    │   ├── button.css
    │   ...
    ..
```

### Output
```
Root/
└── Public/
    └── compressor/
        ├── take.css
        └── take.js
```


# Help, docs and links
- [Help](/docs/help.md)
- [Packagist](https://packagist.org/packages/bvanhoekelen/php-compressor)

# Instalation

## Install with Laravel
Open the `composer.json` file and place the line in `require`.
```json
 "bvanhoekelen/php-compressor": "^1.0"
```

Open the `AppServiceProvider.php` located in `App\Providers\`.
```php
// Add namespace at the top
use PhpCompressor\PhpCompressor;

//Place the code in the `public function boot()`.
if(config('app.debug')) // DON'T RUN ON PRODUCTION !!
    PhpCompressor::run(['../resources/assets/css/', '../resources/assets/js/']);
```

Place the code in the `<head>` from the html file.
```html
<!-- PHP compressor -->
<link href="{{ asset('/compressor/take.css') }}" rel="stylesheet">
<script src="{{ asset('/compressor/take.js') }}"></script>
```

## Install with composer
Get the source code by running the composer comment in the command line 
```{r, engine='bash', count_lines}
 $ composer require bvanhoekelen/php-compressor
```

Run PHP compressor by place the code before the view is draw.
```php
// Require vender autoload
require_once('../vendor/autoload.php');

// Use namespace
use PhpCompressor\PhpCompressor;

// Switch which determines if environment is production
$production = false;

// Run php conpressor
if( ! $production ) // DON'T RUN ON PRODUCTION !!
    PhpCompressor::run(['resources/css/', 'resources/js/'], 'public/');
```

Place the code in the `<head>` from the html file.
```html
<!-- PHP compressor -->
<link href='compressor/take.css' rel='stylesheet'>
<script src='compressor/take.js'></script>
```

