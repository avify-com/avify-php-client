# Avify PHP Client Library

## Requirements
PHP 5.6.0 and later.

## Composer

You can install the bindings via [Composer](https://getcomposer.org/). Run the following command:

``` bash
$ composer require avify/avify-php-client
```

To use the bindings, use Composer's autoload:

```php 
require __DIR__ . '/vendor/autoload.php';
```

## Usage
The Avify PHP Client constructor accepts the following parameters:

- **mode:** `'sandbox'` or `'production'`.
- **version:** `'v1'`.
- **api_key**: The API key provided by Avify.

```php
use App\Avify;

$avify = new Avify(
    'sandbox',
    'v1',
    ' ** your api key here ** '
);

$paymentData = [
    "amount" => 500.00, // It must be a float value with a maximum of 2 decimal.
    "currency" => "USD",
    "description" => "Description",
    "orderReference" => "ref-123",
    "card" => [
        "cardHolder" => "John Doe",
        "cardNumber" => "4242424242424242",
        "cvc" => 123,
        "expMonth" => 12,
        "expYear" => 2028
    ],
    "customerId" => "123456789",
    "customer" => [
        "firstName" => "John",
        "lastName" => "Doe",
        "email" => "johndoe@test.com",
        "company" => "Best Company",
        "shippingAddress" => [
            "addressLine1" => "My address line 1",
            "addressLine2" => "My address line 2",
            "country" => "A valid country",
            "state" => "A valid state",
            "district" => "A valid district",
            "city" => "A valid city",
            "postCode" => "10512",
            "geoLat" => 19.087,
            "geoLon" => 10.246,
            "label" => "Work"
        ],
        "billingAddress" => [
            "addressLine1" => "My address line 1",
            "addressLine2" => "My address line 2",
            "country" => "A valid country",
            "state" => "A valid state",
            "district" => "A valid district",
            "city" => "A valid city",
            "postCode" => "10512",
            "geoLat" => 19.087,
            "geoLon" => 10.246,
            "label" => "Work"
        ],
    ],
    "meta" => ["orderId" => "ord-1234"]
];
$storeId = 25;
$checkout = $avify->process_payment($paymentData, $storeId);
```


## Internationalization
If you want to set a custom locale and show messages in a specific language, you can do so using the `set_locale` method:

```php
// Language and country
$avify->set_locale('es_CR');

// Or you can directly specify the language
$avify->set_locale('es');

```

### Available languages
At the moment we only support Spanish ('es') and English ('en').

**Note:** API's locale is English by default.

## TODO

- Improve README.
