# Config environment loader

[![Build Status](https://travis-ci.com/opxcore/config-environment.svg?branch=master)](https://travis-ci.com/opxcore/config-environment)
[![Coverage Status](https://coveralls.io/repos/github/opxcore/config-environment/badge.svg?branch=master)](https://coveralls.io/github/opxcore/config-environment?branch=master)
[![Latest Stable Version](https://poser.pugx.org/opxcore/config-environment/v/stable)](https://packagist.org/packages/opxcore/config-environment)
[![Total Downloads](https://poser.pugx.org/opxcore/config-environment/downloads)](https://packagist.org/packages/opxcore/config-environment)
[![License](https://poser.pugx.org/opxcore/config-environment/license)](https://packagist.org/packages/opxcore/config-environment)

## Installing

```
composer require opxcore/config-environment
```

## Usage

### Loading environment

To load environment variables from file call `Environment::load($path, $filename)`, where `$path` is directory where
environment file is placed and `$filename` is a name of file. **All variables would be stored locally, not affecting any
of $_ENV, $_SERVER and so on**.

Additional arguments is `load($path, $filename, $safe, $silent)`. If `$safe` is set to `true` variables would not be
overwritten if they are already present. If `$silent` is set to `true` no exceptions would be thrown. Function
returns `true` if environment file wss processed successfully, `false` in case of any errors.

### Reading environment variables

`Environment::get($key, $default)` returns environment variable if it set, otherwise returns value assigned to default
or return value of callable passed to as default.

`Environment::has($key)` returns `true` is environment variable set, `false` otherwise.

### Manipulations

`Environment::set($key, $value, $safe)` sets value to environment. If `$safe` is set to `true` variables would not be
overwritten if they are already present. Passed values of string type it would be parsed (see below), others would be
set as is.

`Environment::unset($key)` removes variable from environment.

## Format

Each line of environment must contain one value, consisting of variable name and its value separated by `=` sign. Other
words, part of line before `=` sign would be used as variable name, another part would be value of variable.

For example:

```dotenv
APP_NAME=My awesome application
```

Each value would be parsed according next rules:

### String

Each quoted value would be converted to unquoted strings.

```dotenv
DB_CONNECTION="mysql"
DB_HOST="127.0.0.1"
```

Result of `Environment::get('DB_HOST')` would be `'127.0.0.1'`.

### Boolean

If value is `true` or `false` it would be converted to `boolean` type.

```dotenv
CACHE_ENABLE=true
304_RESPONSE=false
```

### Null

Null values would set to `null`

```dotenv
CACHE_DRIVER=null
```

### Array
Arrays must start with `[` and ends with `]`. Items must be separated by commas. Each item would be parsed according 
this rules. **Nested arrays are not supported**. 
```dotenv
BROADCAST_DRIVER=[log,telegram]
```

### Numbers
If value represents any of number format it would be converted to integer or float.
```dotenv
COUNT=42
MULTIPLIER=1.05
FLOAT=0.15E-10
```

If parser would not recognise type of value, original string would be returned.
```dotenv
APP_KEY=base64:0vqkPYSbwPm3MOzdxQJ76Ps6pouZRjN5xPx3b+dm628=
```

Result of `Environment::get('APP_KEY')` would be `'base64:0vqkPYSbwPm3MOzdxQJ76Ps6pouZRjN5xPx3b+dm628='`.