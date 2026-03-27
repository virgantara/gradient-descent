<?php
require 'vendor/aws/aws-autoloader.php';

$bucket_name        = "unida-arsip";
$account_id         = "52ca63db06834db1fd23b1dbfc5e8fbf";
$access_key_id      = "af52987294c8780ca34cb5b929c22df3";
$access_key_secret  = "4e5e7bef0fa3263ca5ed1a515b19561792856358f37f75b89bfe24a963bbb1e1";

$credentials = new Aws\Credentials\Credentials($access_key_id, $access_key_secret);

$options = [
    'region' => 'auto',
    'endpoint' => "https://$account_id.r2.cloudflarestorage.com",
    'version' => 'latest',
    'credentials' => $credentials
];

$s3_client = new Aws\S3\S3Client($options);

$contents = $s3_client->listObjectsV2([
    'Bucket' => $bucket_name
]);

var_dump($contents['Contents']);