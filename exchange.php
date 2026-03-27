<?php 
$data = http_build_query([
    'grant_type'    => 'authorization_code',
    'code'          => '9f04f43bd72b87a7a27d3faa8ed7dc13c8143c78',
    'client_id'     => 'abc123',
    'client_secret' => 'abc321',
    'redirect_uri'  => 'http://localhost:3000/callback',
]);

$tokenUrl = 'http//localhost:3000/oauth/token';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

echo '<pre>';

if (curl_errno($ch)) {

    echo 'Error: ' . curl_error($ch);
    // print_r($response);
    // exit;
} 
exit;
curl_close($ch);

$accessToken = json_decode($response, true);

return $accessToken;
 ?>