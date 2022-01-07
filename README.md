# Avify PHP Client Library

## Installation

TODO

## Usage

```php
use App\Avify;

$avify = new Avify(
    'sandbox',
    'v1',
    ' ** your api key here ** '
);

$paymentData = [
    "amount" => 500,
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

## TODO

- Add testing
- Improve README.
