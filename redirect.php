<?php
function redirectToKeycloakLogin() {
    $keycloakBaseUrl = 'http://devcloak.passcess.net/realms/master';
    $clientId = 'keycloak-php-example';
    $redirectUri = 'http://localhost/example/callback.php';

    $authUrl = $keycloakBaseUrl . '/protocol/openid-connect/auth';

    $queryParams = http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'openid'
    ]);

    $redirectUrl = $authUrl . '?' . $queryParams;

    header('Location: ' . $redirectUrl);
    exit();
}

redirectToKeycloakLogin();
?>
