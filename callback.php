<?php
class CallbackComponent {
    private $accessToken = null;
    public $testDisplay = null;

    public function __construct() {}

    public function handleKeycloakCallback($code) {
        $keycloakBaseUrl = 'http://devcloak.passcess.net/realms/master';
        $clientId = 'keycloak-php-example';
        $clientSecret = 'your-client-secret';
        $redirectUri = 'http://localhost/example/callback.php';

        $tokenUrl = $keycloakBaseUrl . '/protocol/openid-connect/token';

        $body = http_build_query([
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'code' => $code
        ]);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $body
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($tokenUrl, false, $context);
        $responseData = json_decode($response, true);

        $this->accessToken = $responseData['access_token'];
        $this->testDisplay = $responseData['access_token'];
        setcookie('accessToken', $this->accessToken, time() + (86400 * 30), "/"); // 86400 = 1 day
        header('Location: /example/index.php');
        exit();
    }
}

$callback = new CallbackComponent();
$code = isset($_GET['code']) ? $_GET['code'] : null;

if ($code) {
    $callback->handleKeycloakCallback($code);
} else {
    header('Location: /login');
    exit();
}
?>
