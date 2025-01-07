# MiniApiBase Project

[![Maintainability]()]()
[![Test Coverage]()]()

MiniApiBase is a PHP Experts, Inc., Project meant to ease the creation of new API projects.

It strives to conform to the Standard PHP Skeleton (https://github.com/php-pds/skeleton) wherever possible.

Read [**On Structuring PHP Projects**](https://blog.nikolaposa.in.rs/2017/01/16/on-structuring-php-projects/)
for more.

The configurer was inspired by https://www.binpress.com/building-project-skeletons-composer/

It includes everything you need for a modern lightweight PHP API server:

* Minimum PHP version of v8.1.
* [minicli/minicli (PHP Experts' fork)](https://github.com/PHPExperts-Contribs/minicli) 
* [pecee/simple-router](https://github.com/skipperbent/simple-php-router) 
* [phpexperts/console-painter](https://github.com/PHPExpertsInc/ConsolePainter) 
* [phpexperts/laravel-env-polyfill](https://github.com/PHPExpertsInc/Laravel57-env-polyfill) 
* [phpexperts/rest-speaker](https://github.com/PHPExpertsInc/RESTSpeaker)
* [phpexperts/simple-dto](https://github.com/PHPExpertsInc/SimpleDTO) 


## Installation

Via Composer

```bash
composer create-project phpexperts/mini-api-base api.my.site
```

## Usage

Install a project, then remove the directories you won't need, like `bin`.

You should definitely edit the LICENSE to be specific to your 
project and update the tags at the top of the README.md.

## Use cases

 ✔ Rapidly start up a project right.  
 ✔ Less time spent on boilerplating a git repo.  
 ✔ Conforms to the most widely-deployed PHP layout.  
 ✔ Fully compatible with the Bettergist Collective recommendation.  

## Testing

```bash
phpunit --testdox
```

## Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE.mit.md) for more information.
