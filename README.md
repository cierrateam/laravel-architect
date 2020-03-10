<p align="center">
    <img title="Laravel Architect" height="300" src="https://user-images.githubusercontent.com/24706440/76251424-3cc58880-6247-11ea-8f46-944896470c6b.png" />
</p>

<p align="center">
  <a href="https://github.com/cierrateam/laravel-architect/issues"><img alt="GitHub issues" src="https://img.shields.io/github/issues/cierrateam/laravel-architect"></a>
  <a href="https://github.com/cierrateam/laravel-architect/network"><img alt="GitHub forks" src="https://img.shields.io/github/forks/cierrateam/laravel-architect"></a>
  <a href="https://github.com/cierrateam/laravel-architect"><img alt="GitHub license" src="https://img.shields.io/github/license/cierrateam/laravel-architect"></a>
</p>

<h4> <center>This package is under construction. Feel free to contribute. </center></h4>

Laravel Architect was created by, and is maintained by [Vittorio Emmermann](https://github.com/vittoroe), founder of cierra. It's a helper you can install global to your machine or local to your package to make it easier to develop them. So for example if you want to create a test to your laravel package, you can easily do this with `architect make:test MyAwesomeTest`

- Built on top of the [Laravel Zero](https://laravel-zero.com/).
- Installable global to your machine as general helper.
- Also useable locally in single projects.

------

## Installation

To install Laravel Architect we recomment to install it globally to gather the best usage of this package:<br />
`composer global require "cierrateam/laravel-architect"`

But at the moment you have to add `"minimum-stability": "dev"` to your global composer config, because laravel zero is only available in dev for laravel 7. Sorry for that, we'll update here asap.

## Usage

Here is work in progress :) Feel free to contribute.

### Create a new package
``` 
architect init
``` 
Then just answer the questions.

### Create a new test
``` 
architect make:test ExampleTest
```
A test will be created

## Roadmap
- [x] Creating Packages with architect
- [x] Creating Tests with architect
- [ ] Creating Models, Controllers and migrations
- [ ] Customising stubs
- [ ] More options while creating packages
- [ ] Run package tests with architect
- [ ] Install local path packages with architect in projects

## License

Laravel Architect is an open-source software licensed under the [MIT license](https://github.com/laravel-architect/laravel-architect/blob/stable/LICENSE.md).

## Credits
This package is made by cierra and is coded with support of community packages.

<a href="https://cierra.de">
    <img title="cierra" height="50" src="https://assets.website-files.com/5d481a8da904cda6ec05cf74/5d481a8da904cdba4d05cfad_cierra-logo.png" />
</a>
