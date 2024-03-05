<?php
function logoutFromKeycloak() {
    

    $keycloakBaseUrl = 'http://devcloak.passcess.net/realms/master';
    $clientId = 'keycloak-php-example';
    $logoutUrl = $keycloakBaseUrl . '/protocol/openid-connect/logout';

    $queryParams = http_build_query([
        'client_id' => $clientId
    ]);

    $redirectUrl = $logoutUrl . '?' . $queryParams;

    header('Location: ' . $redirectUrl);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutFromKeycloak();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch User Info and Logout</title>
</head>
<body>
    <h1>Fetch User Info and Logout</h1>
    <form method="post">
        <button type="submit" name="fetchUserInfo">Fetch User Info</button>
    </form>
    <div id="userInfo">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_COOKIE['accessToken'])) {
            $accessToken = $_COOKIE['accessToken'];

            $keycloakBaseUrl = 'http://devcloak.passcess.net/realms/master';
            $userInfoURL = $keycloakBaseUrl . '/protocol/openid-connect/userinfo';

            $headers = [
                'Authorization: Bearer ' . $accessToken
            ];

            $options = [
                'http' => [
                    'header' => implode("\r\n", $headers),
                    'method' => 'POST'
                ]
            ];

            $context = stream_context_create($options);
            $response = file_get_contents($userInfoURL, false, $context);

            if ($response !== FALSE) {
                echo '<pre>' . $response . '</pre>';
            } else {
                echo 'Error occurred while fetching user info.';
            }
        } else {
            echo 'Access token not found.';
        }
        ?>
    </div>
<hr>
    <form method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
