

# PHP compressor
[![Hex.pm](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000&style=flat-square)](https://github.com/bvanhoekelen/php-compressor/blob/master/LICENSE)
[![Packagist Prerelease](https://img.shields.io/packagist/vpre/bvanhoekelen/php-compressor.svg?style=flat-square)](https://packagist.org/packages/bvanhoekelen/php-compressor)
[![Packagist](https://img.shields.io/packagist/dt/bvanhoekelen/php-compressor.svg?style=flat-square)](https://packagist.org/packages/bvanhoekelen/php-compressor)
[![Github issues](https://img.shields.io/github/issues/bvanhoekelen/php-compressor.svg?style=flat-square)](https://github.com/bvanhoekelen/php-compressor/issues)
[![Travis branch](https://img.shields.io/travis/bvanhoekelen/php-compressor/master.svg?style=flat-square)](https://travis-ci.org/bvanhoekelen/php-compressor)
[![Travis branch](https://img.shields.io/travis/bvanhoekelen/php-compressor/develop.svg?style=flat-square)](https://travis-ci.org/bvanhoekelen/php-compressor) Build: Master|Develop

<p align="center"><img src="/assets/banner.png" alt="php-compressor" /></p>

## Highlight
- Merge several files into one file
- Live compiler, fast and no additional program is needed
- Allows user to write code that can be used in other projects » [Code reuse](https://en.wikipedia.org/wiki/Code_reuse)
- The ability to set variables » [see variable in help](https://github.com/bvanhoekelen/php-compressor/wiki)
- Support .css, .fcss and .js files
- Required no changes on the live server
- Reducing server load and client load time
- Optimizing assets for a higher rank in google search results » [PageSpeed](https://developers.google.com/speed/pagespeed/)
- Easy to install » [instalation](#instalation)
- Support for Laravel framework » [Laravel](https://laravel.com)

## Workflow
- Input
    - Contains the building blocks
    - Folder can be placed before the public folder, no access for external users
    - For a better overview you can split easy your code in into several files
    - No `@include`, files are automatically merged
    - Ordering happened by name
    
- Output
    - Each extension has its own file
    - Use normal html tag `<link href='compressor/take.css' rel='stylesheet'>` and `<script src='compressor/take.js'></script>` to grab it
    
- PHP compressor run (PhpCompressor::run())
    - &lt;loccation&gt; (INPUT) directory where the .CSS, .FCSS and .JS files are
    - &lt;destination&gt; (OUTPUT) directory that contains the `compressor/` folder. _Note. de destination *path* is without the `compressor/`. This wil set in automatic_
    - The output of PHP compressor wil set in the `compressor/` folder as `take.*`
    - Run PHP compressor only in the developor environment, not in production!
   
```php
    (Input)                                                           (Output)
    
Root/                                           .               Root/
└── Resources/             .. ..................;;.             └── Public/ (!)
    └── css/ (!)              .. PHP compresspr ;;;;.               └── compressor/ (static)
    │   ├── table.css    . . .::::::::::::::::::;;:'                   ├── take.css
    │   ├── alert.css                           :'                     └── take.js
    │   ├── button.css
    │   ...
    └── js/  (!)
    │   ├── table.js
    │   ├── alert.js
    │   ├── button.js
    │   ...
    ..

                             (PHP compressor)
 
 PhpCompressor::run(['resources/assets/css/', 'resources/assets/js/'], 'public/');
 PhpCompressor::run( [ <loccation> , <location>, ... ], <destination> ); // explanation!
    
```

# Help, docs and links
- [Help](https://github.com/bvanhoekelen/php-compressor/wiki)
- [Packagist](https://packagist.org/packages/bvanhoekelen/php-compressor)

# Instalation

## Install with Laravel
Get PHP compressor by running the composer command in the command line. 
```{r, engine='bash', count_lines}
 $ composer require bvanhoekelen/php-compressor
```

Open the `AppServiceProvider.php` located in `App\Providers\`.
```php
// Add namespace at the top
use PhpCompressor\PhpCompressor;

// Place the code in the `public function boot()`.
if(config('app.debug')) // DON'T USE ON PRODUCTION !!
    PhpCompressor::run(['resources/assets/css/', 'resources/assets/js/'], 'public/');
```

Place the code in `<head>` from the html file.
```html
<!-- PHP compressor -->
<link href="{{ asset('/compressor/take.css') }}" rel="stylesheet">
<script src="{{ asset('/compressor/take.js') }}"></script>
```

## Install with composer
Get PHP compressor by running the composer command in the command line. 
```{r, engine='bash', count_lines}
 $ composer require bvanhoekelen/php-compressor
```

Run PHP compressor by place code before the view is draw.
```php
// Require vender autoload
require_once('../vendor/autoload.php');

// Use namespace
use PhpCompressor\PhpCompressor;

// Switch which determines if environment is production
$production = false;

// Run php conpressor
if( ! $production ) // DON'T USE ON PRODUCTION !!
    PhpCompressor::run(['resources/css/', 'resources/js/'], 'public/');
```

Place the code in `<head>` from the html file.
```html
<!-- PHP compressor -->
<link href='compressor/take.css' rel='stylesheet'>
<script src='compressor/take.js'></script>
```

