# PHP Color Hash
PHP Port of [ColorHash Javascript Library](https://github.com/zenozeng/color-hash)

## Usage

### Composer

```bash
composer require shahonseven/php-color-hash
```
#### Basic

```php
$colorHash = new Shahonseven\ColorHash();

// in HSL, Hue ∈ [0, 360), Saturation ∈ [0, 1], Lightness ∈ [0, 1]
$colorHash->hsl('Hello World'); // [ 225, 0.65, 0.35 ]

// in RGB, R, G, B ∈ [0, 255]
$colorHash->rgb('Hello World'); // [ 135, 150, 197 ]

// in HEX
$colorHash->hex('Hello World'); // '#8796c5'
```
