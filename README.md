# Laravel Microsoft Graph
This Laravel package provides a convenient wrapper for the Microsoft Graph PHP library.

[![Latest Version](https://img.shields.io/packagist/v/noardcode/laravel-microsoft-graph.svg?style=flat-square)](https://packagist.org/packages/noardcode/laravel-microsoft-graph)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/noardcode/laravel-microsoft-graph.svg?style=flat-square)](https://packagist.org/packages/noardcode/laravel-microsoft-graph)

## Installation

```bash
composer require noardcode/laravel-microsoft-graph
```

### Publish configuration

Publish the supplied configuration file (config/microsoft-graph.php). 
This file should be used to configure your Graph API connection.

```bash
php artisan vendor:publish --tag="config"
```

### Register your application with the Microsoft identity platform

To connect with the Microsoft Graph API you first need to register your application with the
Microsoft identity platform. This platform will provide you with all the required IDs en secrets:

* Client ID
* Client Secret
* Tenant ID
* Object ID

Registering your application can be done through the [Microsoft Azure portal](https://portal.azure.com).
In the menu select "Azure Active Directory" > "App Registrations" > "New registration" to create a new registration.

In the registration form you will be asked to provide a "Redirect URI".
You'll find more information about this URI in the "xyz" section below.

For more information on the application registration visit the [Microsoft Documentation](https://docs.microsoft.com/en-us/graph/auth-register-app-v2?context=graph/api/1.0). 

## How to use this package
Besides easy access to several Microsoft Graph API endpoints this package
provides an easy to use OAuth2.0 wrapper to authorize with the API/Microsoft identity platform. 

The authorization requires two routes:
* The first route redirects the user to the Microsoft identity platform to let the user consent with the requested permissions your applications requests;
* The second (GET) route (or redirect_uri) is the URI the Microsoft identity platform redirects to after consent has been given (webhook).

Below is an example controller for handling these:

```php
<?php

namespace App\Http\Controllers;

use Noardcode\MicrosoftGraph\Requests\AuthorizationRequest;
use Noardcode\MicrosoftGraph\MicrosoftGraphClient;
use Noardcode\MicrosoftGraph\ValueObjects\AuthorizationResponse;

/**
 * Example implementation of the noardcode/laravel-microsoft-graph package.
 *
 * Class Oauth2Controller
 * @package App\Http\Controllers
 */
class Oauth2Controller extends Controller
{
    /**
     * @var MicrosoftGraphClient
     */
    protected MicrosoftGraphClient $client;

    /**
     * This should be a unique/random string that is used to confirm the received request on the webhook
     * belongs to the made authorization request. It must therefore be the same in both methods.
     *
     * @var string
     */
    protected string $state = '1234567890';

    /**
     * Set the client.
     *
     * Oauth2Controller constructor.
     * @param MicrosoftGraphClient $client
     */
    public function __construct(MicrosoftGraphClient $client)
    {
        $this->client = $client;
    }

    /**
     * Redirect the user to the Microsoft identity platform.
     * A method like this could for example be called after clicking a button to connect with Microsoft 365.
     */
    public function getUserConsent(): void
    {
        $this->client->authorize($this->state);
    }

    /**
     * The webhook that is called by the Microsoft identity platform after the user has given his/her consent.
     * The AuthorizationRequest class that is provided by the package immidiatly validates the response.
     *
     * With this response an access token is requested and returned.
     * Save this access token (for example serialized in the databases users table) for later usage when you want to
     * perform requests on the Graph API.
     *
     * @param MicrosoftGraphClient $client
     * @param AuthorizationRequest $request
     */
    public function getToken(MicrosoftGraphClient $client, AuthorizationRequest $request)
    {
        $accessToken = $client->requestAccessToken(
            new AuthorizationResponse($request->all()),
            $this->state
        );

        // Todo: Save the access token, for example by serializing the object (serialize($accessToken)).
    }
}
```

Be sure to add the correct redirect URI to the configuration or .env file.

### Determine permissions
Permissions determine which resources your application will have access to.
These permissions will be shown to the user when the consent is requested and can be set
in the configuration as a space seperated string.

More information on the available permissions can be found in the [permission reference](https://docs.microsoft.com/en-us/graph/permissions-reference?context=graph%2Fapi%2F1.0&view=graph-rest-1.0).  

### Doing requests
First instantiate the MicrosoftGraphClient with the retrieved access token as constructor argument:

```php
$client = new MicrosoftGraphClient($accessToken);
```

or via the setter:

```php
$client = new MicrosoftGraphClient();
$client->setAccessToken($accessToken);
```

Now you can retrieve the original Microsoft Graph library directly:

```php
$client->getGraph();
```

This way you can access all available endpoints [as described with this package](https://packagist.org/packages/microsoft/microsoft-graph).
An other way to use this package is to use convenient predefined methods, for example:

```php
$client->users()->get();
```

Not all data is already accessible via these methods but for the ones that are
they provide an easy interface with the Microsoft Graph API which also return
ready to use value objects and/or collections.

Currently the following methods are implemented:
* users() // Retrieve information of the signed in user or other organization users.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Contributions are **welcome** and will be fully **credited**. We accept contributions via Pull Requests on [Github](https://github.com/noardcode/laravel-microsoft-graph).

### Pull Requests

- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - The easiest way to apply the conventions is to install [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer).
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Create feature branches** - Don't ask us to pull from your master branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## References
* [Microsoft Graph dev center](https://developer.microsoft.com/en-us/graph)
* [Microsoft Graph documentation](https://docs.microsoft.com/en-us/graph/)
* [Microsoft Azure portal](https://portal.azure.com)
