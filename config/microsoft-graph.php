<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application details.
    |--------------------------------------------------------------------------
    |
    | The client_id, tenant_id and object_id are provided after registering your
    | application with Microsoft. To register your own application and retrieve
    | these details visit https://docs.microsoft.com/en-us/graph/auth-register-app-v2
    | for more details.
    |
    */

    'client_id' => env('MSGRAPH_CLIENT_ID'),
    'client_secret' => env('MSGRAPH_CLIENT_SECRET'),
    'tenant_id' => env('MSGRAPH_TENANT_ID'),
    'object_id' => env('MSGRAPH_OBJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | OAuth 2.0 authorization.
    |--------------------------------------------------------------------------
    |
    | The Microsoft Graph packages comes without OAuth 2.0 authorization.
    | The NoardCode Laravel Microsoft Graph package solves this with a simple
    | implementation.
    |
    | For OAuth 2.0 authorization the Microsoft Graph endpoint is required. The
    | endpoint accepts one placeholder: [TENANT_ID] which will be replaced by
    | the actual value.
    |
    | The redirect URI is the webhook URI Microsoft will call after user consent.
    |
    | Privileges is a space separated string with all privileges the application
    | is requesting from Microsoft Graph. More information about available
    | privileges can be found here: https://docs.microsoft.com/en-us/graph/permissions-reference
    */

    'oauth2' => [
        'endpoint' => 'https://login.microsoftonline.com/[TENANT_ID]/oauth2/v2.0',
        'redirect_uri' => env('MSGRAPH_OAUTH2_REDIRECT_URI', '/laravel-microsoft-graph/get-token'),
        'permissions' => env(
            'MSGRAPH_OAUTH2_PERMISSIONS',
            'offline_access user.read User.ReadBasic.All User.ReadWrite.All'
        )
    ],

    /*
    |--------------------------------------------------------------------------
    | Graph API settings.
    |--------------------------------------------------------------------------
    |
    | The default endpoint for API calls en the version of the API to use.
    |
    */

    'api' => [
        'endpoint' => 'https://graph.microsoft.com',
        'version' => 'v1.0', // "beta" is also an option.
    ]
];
