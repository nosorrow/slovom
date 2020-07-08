## Number to word & Currency to word (BGN to word)

### Getting Started

```text
git clone https://github.com/nosorrow/slovom.git
```
Or  

Make composer.json file and Just tell composer to use source if available:
Copy this in your composer.json

```
{
  "name": "plamenorama/numbertowodrd",
  "description": "Number to word",
  "license": "MIT",
  "authors": [
    {
      "name": "Plamen Petkov",
      "email": "plamenorama@gmail.com"
    }
  ],
  "require": {
    "php": ">=5.4.0",
    "plamenorama/numbertoword": "^1.1"
  },

  "require-dev": {
    "phpunit/phpunit": "5.*"
  },
  "minimum-stability": "dev",
  "prefer-stable": true,
  "repositories": [
    {
      "type": "package",
      "package": {
        "name": "plamenorama/numbertoword",
        "version": "1.1",
        "source": {
          "url": "https://github.com/nosorrow/slovom.git",
          "type": "git",
          "reference": "master"
        },
        "autoload": {
          "files": [
            "NumberToWord.php"
          ]
        }
      }
    }]
}

```
and
```
composer install

```

### Currency

```php

<?php

$chislo = 55.32;

$numtoword = new NumberToWord('currency');

// Default option;
$options = [
    'suffix' => 'лв.', 
    'fraction' => 'ст.', 
    'and' => 'и', 
    'negative_word' => 'минус', 
    'negative' => false, 
    'exp' => 12, // max number is 999*10E12+999
    'decimal' => 2 // The number of decimal digits to round to.
];
$numtoword->setOptions($opttions);

$num = $numtoword->setNumber($chislo);

echo $num->toNumber() . ' ==> ' . $num->toWord();

// output : 55.32 ==> петдесет и пет лв. и тридест и две ст.


$num = $numtoword->setNumber($chislo, false); 

echo $num->toNumber() . ' ==> ' . $num->toWord();

// output : 55.32 ==> петдесет и пет лв. и 32 две ст.

```

### II. Get word:

```php

// second param is optional. true by default.

echo $numtoword->slovom($chislo, true); 

```

### Number

```php

<?php

$chislo = 552.32;

$numtoword = new NumberToWord('number');

// Default option;
$options = [
    'suffix' => 'цяло', 
    'fraction' => '', 
    'and' => 'и', 
    'negative_word' => 'минус', 
    'negative' => true, 
    'exp' => 12, 
    'decimal' => false
];

$numtoword->setOptions($opttions);

$numtoword->setNumber($chislo);

echo $numtoword->toNumber() . ' ==> ' . $numtoword->toWord();

```
