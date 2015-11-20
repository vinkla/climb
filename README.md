# Composer Climb

![climb](https://cloud.githubusercontent.com/assets/499192/11169131/34667b2c-8baf-11e5-99d7-88c2e4cb0330.png)

A Composer version manager tool made with inspiration from [this awesome NPM package](https://www.npmjs.com/package/npm-check-updates). Find newer versions of dependencies than what your composer.json allows.

```bash
alt-three/logger                1.0.2      →     1.1.0
graham-campbell/exceptions      5.0.0      →     5.1.0
jenssegers/optimus              0.1.4      →     0.2.0
vinkla/hashids                  1.1.0      →     2.2.0
```

[![Build Status](https://img.shields.io/travis/vinkla/climb/master.svg?style=flat)](https://travis-ci.org/vinkla/climb)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/vinkla/climb.svg?style=flat)](https://scrutinizer-ci.com/g/vinkla/climb/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/vinkla/climb.svg?style=flat)](https://scrutinizer-ci.com/g/vinkla/climb)
[![Latest Version](https://img.shields.io/github/release/vinkla/climb.svg?style=flat)](https://github.com/vinkla/climb/releases)
[![License](https://img.shields.io/packagist/l/vinkla/climb.svg?style=flat)](https://packagist.org/packages/vinkla/climb)

> **Note:** This tool is still in early stages of development and may change in the future. The tool is a proof of concept. There are some bugs that we're aware of and we will try to fix them asap. If you find any please report! We also appreciate pull requests, a lot!

## Installation

You can install Climb with either [Homebrew](http://brew.sh/) (recommended) or globally with [Composer](https://getcomposer.org/).

### Homebrew

Run this command to install Climb with Homebrew.

```bash
brew install homebrew/php/climb
```

### Composer

Run this command to install Climb globally with Composer.
```bash
composer global require vinkla/climb
```

Be sure to have composer binaries in your `$PATH`:
```
export PATH=${PATH}:${HOME}/.composer/vendor/bin;
```

## Usage

Find newer versions of dependencies than what your `composer.json` allows.
```bash
climb
```

Find newer versions of dependencies than what your global `composer.json` allows.
```bash
climb global
```

Update `composer.json` dependencies versions.
```bash
climb update

# Update breaking version
climb update --all
```

Update global `composer.json` dependencies versions.
```bash
climb global-update

# Update breaking version
climb global-update --all
```

## License

Climb is licensed under [The MIT License (MIT)](LICENSE).
