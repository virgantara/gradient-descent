<?php

$token = '5078a98179bd2ca9fc95a50706de39a0';
$api_url = 'https://local.moodle.com/webservice/rest/server.php';
$username = 'admin';
$password = 'dK^UW=k4g*wVLpp';


$courseData = array(
    'courses' => array(
        array(
            'fullname' => 'New Course Full Name',
            'shortname' => 'new_course',
            'categoryid' => 1, // Replace with the actual category ID
            'summary' => 'Description of the new course.',
            'format' => 'weeks',
            'numsections' => 10,
            'startdate' => strtotime('2023-01-01'), // Replace with the desired start date
            'visible' => 1, // Set to 0 if you want the course to be hidden initially
        ),
    ),
);


// Example request to get site info
$params = array(
    'wstoken' => $token,
    'wsfunction' => 'core_user_get_users',
    'moodlewsrestformat' => 'json',
    // 'courses' => json_encode($courseData['courses']),
    'criteria' => array(
        array(
            'key' => 'username',
            'value' => 'admin',
        ),
    )
);



$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

$response = curl_exec($ch);

curl_close($ch);

// Process the response
$result = json_decode($response, true);

if ($result === false) {
    echo "Error: Unable to retrieve site info. Check your parameters and permissions.";
} else {
    echo '<pre>';
    print_r($result);
    exit;
}
