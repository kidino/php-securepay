# PHP SecurePay Library (Malaysia)

SecurePay is a payment solution that supports FPX (for now) in Malaysia.

This is my simple class library to use SecurePay with your PHP applications. In this example, I am using it with my Laravel application. 

## How to use

1. Copy the SecurePay.php to `/app/Support/Library/PaymentGateway/SecurePay.php`
2. update your `.env` file with your SecurePay API credentials

```
SECUREPAY_ENV=sandbox|production
SECUREPAY_UID=96b21cca-cf50-4839-sample-uid
SECUREPAY_AUTH_TOKEN=m6CMt3xy-sample-auth-token
SECUREPAY_CHECKSUM_TOKEN=96f40ce4441603b957ecd57ea1d96b8c143b-sample-checkum-token
```
