<?php
$push_api_key = 'AIzaSyD75eOpS3dk3zjjDi5fwYic5LwpVGaY7Ws';
$push_project = '567105785293';
$receiver_ids = array(
  //'APA91bG7qaIVRBgNX9jfv_REl90P_39QMhVmxpdYTrNQn7M_vGYlJsEwwOG4GY0AixWdxU7k-_CZfCpsbb2tyRvtEDs8f0tHIv00BftureyF7smRKJAhDPAgwbds1E46ItjKDoPu0pKJlv_LXzkCE11pAZABShXiDQ0mudL3w6PXOReaNWRpQhM'
  'APA91bFygr-MVdvyf09XzYS1kk7IErKfpn_JbCl3-rMbSedSCB2x6uA2Ssm1ogvBkVfdP4gG0jLXJueOiXrD1kx1GzeX7BmK8VvT5Ki562P3oKVpyDzpwk48o3pCUi3pPQ22Va0aZUW-v1qjieGlgr_v_kkuBDHkTA'
);

if ($_POST['msgtype'] == 'message') {
  $data = array(
    'registration_ids' => $receiver_ids,
    'data' => array(
      'message' => $_POST['msg']
    ),
  );
}
else {
  // payload
  $data = array(
    'registration_ids' => $receiver_ids,
    'data' => array(
      'debug' => true,
      'cmd' => 'setbaseurl',
      'baseurl' => 'https://healthchallenge.dev.mizar-it.nl'
      //'baseurl' => 'https://app.health-challenge.nl'

    ),
  );
}
$url = 'https://android.googleapis.com/gcm/send';

$headers = array(
  'Authorization: key=' . $push_api_key,
  'Content-Type: application/json'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$output = curl_exec($curl);
echo '<pre>';
var_dump(json_decode($output));
echo '</pre>';
exit;