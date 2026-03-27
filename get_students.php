<?php

$studentsUrl = 'http://local.oauth.com:5001/app/list';

// Read the access token from the file
$accessToken = '8cf1ea5fb93d0c628df41cbee80289ee428b9a43';//file_get_contents('access_token.txt');

if (!$accessToken) {
    die('No access token found. Please run get_access_token.php first.');
}

// Prepare the GET request with the Authorization header
$options = [
    'http' => [
        'header'  => "Authorization: Bearer $accessToken\r\n",
        'method'  => 'GET',
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($studentsUrl, false, $context);

if ($response === FALSE) {
    die('Error accessing the students resource');
}

// Display the list of students
$students = json_decode($response, true);
// echo "List of Students:\n";
echo '<pre>';
print_r($students);

?>
