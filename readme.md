# Uuid Generator

[![Total Downloads](https://poser.pugx.org/fkulakov/uuid/downloads.svg)](https://packagist.org/packages/fkulakov/uuid)
[![codecov.io](http://codecov.io/github/fkulakov/uuid/coverage.svg?branch=master)](http://codecov.io/github/fkulakov/uuid?branch=master)
[![Build Status](https://secure.travis-ci.org/fkulakov/uuid.png?branch=master)](http://travis-ci.org/fkulakov/uuid)

Class to generate a universally unique identifier (UUID) according to the RFC 4122 standard. 
Only support for version 5 UUIDs are built-in.

## Installation

```shell
composer require "fkulakov/uuid"
```

## Usage

For a repeatable generate a UUID from some _$source_ string use source() method:

```php
Uuid::source($source)->generate();
```
	
For unrepeatable generate a random UUID use random() method:

```php
Uuid::random()->generate();
```

For change namespace use setNamespace() method:

```php
Uuid::random()->setNamespace($namespace)->generate(); 
```

```php
Uuid::source($source)->setNamespace($namespace)->generate(); 
```

```php
Uuid::random()->setNamespace($namespace)->generate(); 
```

_NAMESPACE_DNS_ is used by default.

## Notes

The UUID specification: [http://tools.ietf.org/html/rfc4122](http://tools.ietf.org/html/rfc4122).
