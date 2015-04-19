<?php

$config = array(
  'client_id' => 'dqt9FpxnWqQ',
  'app_secret' => '9422324c7a9a58ea2ea06b59756e98554068f3af',
  'connection' => array(
    'server' => 'localhost',
    'database' => 'healthchallenge',
    'username' => 'healthchallenge',
    'password' => 'healthchallenge'
  ),
  'fitbit_consumer_key' => '7d205d24476245a7b3d400a79b095d2f',
  'fitbit_consumer_secret' => 'a6f23493b4f14464a864b1e9d176c748',
  'push_google' => array(
    'enabled' => true,
    'api_server' => 'https://android.googleapis.com/gcm/send',
    'api_key' => 'AIzaSyD75eOpS3dk3zjjDi5fwYic5LwpVGaY7Ws'
  ),
  'push_ios' => array(
    'enabled' => true,
    'api_server' => 'ssl://gateway.sandbox.push.apple.com:2195',
    'api_passphrase' => 'm00nr1s1n@',
    'api_certificate' => 'apn.pem'
  )
);

?>