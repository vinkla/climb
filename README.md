# Composer Climb

Find newer versions of dependencies than what your composer.json allows.

![climb](https://cloud.githubusercontent.com/assets/499192/9735244/a9564544-5639-11e5-8bd2-e108f3d340c1.png)

A Composer version manager tool made with inspiration from [this awesome npm package](https://www.npmjs.com/package/npm-check-updates).

[![Build Status](https://img.shields.io/travis/vinkla/climb/master.svg?style=flat)](https://travis-ci.org/vinkla/climb)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/vinkla/climb.svg?style=flat)](https://scrutinizer-ci.com/g/vinkla/climb/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/vinkla/climb.svg?style=flat)](https://scrutinizer-ci.com/g/vinkla/climb)
[![Latest Version](https://img.shields.io/github/release/vinkla/climb.svg?style=flat)](https://github.com/vinkla/climb/releases)
[![License](https://img.shields.io/packagist/l/vinkla/climb.svg?style=flat)](https://packagist.org/packages/vinkla/climb)

> **Note:** This tool is still in early stages of development and may change in the future. The tool is a proof of concept. There are some bugs that we're aware of and we will try to fix them asap. If you find any please report! We also appreciate pull requests, a lot!

## Installation

Run this command to install the CLI tool globally.
```bash
composer global require vinkla/climb
```

Be sure to have composer binaries in your $PATH:
```
export PATH=${PATH}:${HOME}/.composer/vendor/bin;
```

This is required in order to use this tool.

## Usage

From a directory where you've a `composer.json` file run the command below to check the packages:
```bash
climb
```

If you want to check your global composer packages for outdated versions you can use:
```bash
climb global
```

## License

Climb is licensed under [The MIT License (MIT)](LICENSE).
