# Composer Climb

![climb](https://cloud.githubusercontent.com/assets/499192/11169131/34667b2c-8baf-11e5-99d7-88c2e4cb0330.png)

A Composer version manager tool made with inspiration from [this NPM package](https://www.npmjs.com/package/npm-check-updates). Find newer versions of dependencies than what your composer.json allows.

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

## Installation

You can install Climb with either [Homebrew](http://brew.sh/) (recommended), manually or with [Composer](https://getcomposer.org/).

#### Homebrew

Run this command to install Climb with Homebrew.

```bash
brew install homebrew/php/climb
```

#### Manually

You can run the commands below to easily access `climb` from anywhere on your system.

```bash
wget https://github.com/vinkla/climb/releases/download/0.6.1/climb.phar
chmod +x climb
sudo mv climb.phar /usr/local/bin/climb
climb --version
```

You may also use the downloaded `PHAR` file directly:

```bash
wget https://github.com/vinkla/climb/releases/download/0.6.1/climb.phar
php climb.phar --version
```

#### Composer

Run this command to install Climb globally with Composer.
```bash
composer global require vinkla/climb
```

Be sure to have composer binaries in your `$PATH`:
```
export PATH=${PATH}:${HOME}/.composer/vendor/bin;
```

## Usage

All Climb commands can does have an optional flag called `--global` or `-g` to run on your globally installed packages.

#### Outdated

Find newer versions of dependencies than what your `composer.json` allows.
```bash
climb
# or
climb outdated
```

> In order to send flags to this command you must write it out as `climb outdated`. Running for example `climb --global` wont work. Instead run `climb outdated --global` to get the correct feedback.

#### Update

Update `composer.json` dependencies versions.
```bash
climb update
```

## License

Climb is licensed under [The MIT License (MIT)](LICENSE).
