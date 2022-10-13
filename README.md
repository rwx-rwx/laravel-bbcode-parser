# Laravel BBCode Parser

## What is BBCode
### <a href="https://en.wikipedia.org/wiki/BBCode">BBCode on wikipedia</a>

## How does it work?
This package parse `bbcode` tags to html. 

## Install

Via Composer

``` bash
composer require rwx-rwx/laravel-bbcode-parser
```

## Usage With Laravel
To parse some text it's as easy as this!

```php
use Rwxrwx\BBCode\Facades\BBCode;

echo BBCode::parse('[b]Text![/b]');
// The result is '<strong>Text!</strong>' 
```

<hr>

### Parse only selected tags.
```php
echo BBCode::only(['bold', 'italic'])
        ->parse('[b][u]text[/u] [i]text[/i]![/b]');
/**
 * <strong>
 *  [u]Text[/u]
 *  <span style="font-style: italic;">text</span>
 * </strong> 
 */

echo BBCode::only('bold', 'italic')
        ->parse('[b][u]text[/u] [i]text[/i]![/b]');
```

<hr>

### Parse all except one or more tags.
```php
echo BBCode::except('bold')
        ->parse('[b]text[/b] [i]text[/i]');
/**
 * [b]text[/b]
 * <span style="font-style: italic;">text</span> 
 */
```
<hr>

### Case sensitive & insensitive
By default, the parser is case sensitive.

```php
# Case insensitive
echo BBCode::parse('[b]Bold[/b] [I]Italic![/I]', true); 

# or other way
echo BBCode::parseCaseInsensitive('[b]Bold[/b] [i]Italic[/i]');
```
<hr>

### Strip or remove all bbcode tags
```php
BBCode::stripBBCodeTags('[b]Bold[/b] [i]Italic![/i]');
```

<hr>

#### Laravel Blade

```blade
@bb('[b]Bold[/b] [i]Italic[/i]') 
{{-- <strong>Bold</strong> <em>Italic</em> --}}

@bbexcept('bold', '[b]Bold[/b] [i]Italic[/i]') 
{{-- [b]Bold[/b] <em>Italic</em> --}}

@bbonly('bold', '[b]Bold[/b] [i]Italic[/i]')
{{-- <strong>Bold</strong> [i]Italic[/i] --}}
```

## Extending or editing BBCode tags
Can add custom bbcode tags inside config file
```bash
php artisan vendor:publish --provider="Rwxrwx\BBCode\BBCodeServiceProvider" --tag="bbcodes-config"
```

Or you can add using method
```php
<?php

namespace App\Providers;

use Rwxrwx\BBCode\Facades\BBCode;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        BBCode::addTag(
            name:    'size',
            //                  $1      $2
            search: '/\[size\=([1-7])\](.*?)\[\/size\]/s',
            replace: '<span style="font-size: $1px;">$2</span>',
            content: '$2' // content param
        );
    }
}

```

Using
```php
BBCode::parse('[size=2]text[/size] [b]Example[/b]');
BBCode::except('size')->parse('[size=2]text[/size] [b]Example[/b]');
BBCode::only('size')->parse('[size=2]text[/size] [b]Example[/b]');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
