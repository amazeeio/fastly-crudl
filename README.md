# Fastly-CRUDL

PHP wrapper for Fastly API

Requires Fastly API key and service id environment variables to be set.

For example:

```bash
export FASTLY_API_KEY="2arxxxxxxxxxxxxxxxxxxxxxxxxRIL"
export FASTLY_SERVICE_ID="5CLxxxxxxxxxxxxxxxxxxOoLP"
````

or store inside `.env` file


## Usage

You can install the package via composer:
```
composer require ....
```

Connect to a Fastly API by creating a new Fastly object with an API token and Service ID:

```
use Fastly\Fastly;

...

$fastly_api_key = getenv('FASTLY_API_KEY');
$fastly_service_id  = getenv('FASTLY_SERVICE_ID');

$fastly = new Fastly($fastly_api_key, $fastly_service_id);

```

Then you are able to send requests to the API.


### Generic Send Requests

You can define the method, API uri path and provide options which will go ito the body of the request here.

```php
// Get stats
$stats = $fastly->send('GET', 'stats?from=1+day+ago');

// Get Keys
$keys = $fastly->send('GET', 'tls/private_keys');

// Get Single Key
$key = $fastly->send('GET', 'tls/private_keys/2XbVFa2kUN1d4rGDBFYkzp');

// Get Domains
$domain = $fastly->send('GET', 'service/'. $fastly_service_id .'/version/1/domain/check_all');
$domains = $fastly->send('GET', 'tls/domains');

// Get activations
$activations = $fastly->send('GET', 'tls/activations');

// Get Certificates
$certificates = $fastly->send('GET', 'tls/certificates');
$certificate = $fastly->send('GET', 'tls/certificates/1JP0gerEJXIxImRnRLckug');

// Purge all
$purge = $fastly->send('POST', 'service/'. $fastly_service_id .'/purge_all');
```

### Private Keys

```php

$response = $fastly->send_private_key($cert_d_1);

```

### Certificates    

```php

$response = $fastly->send_tls_certificate($cert_d_1);

```

