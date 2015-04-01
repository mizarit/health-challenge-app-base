<?php

if (!isset($_SERVER['HTTPS'])) {
  $url = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
  header("Location: {$url}");
  exit;
}
ini_set('display_errors', true);
error_reporting(E_ALL);

// core init
require('bootstrap-cli.php');

if (!isset($_COOKIE['xid_id'])) {
  $jawbone_user_id = time().rand(1000000,9999999);
}
else if (isset($_GET['ju'])) {
  $jawbone_user_id = $_GET['ju'];
} else {
  $jawbone_user_id = $_COOKIE['xid_id'];
}

if (!session_id()) session_start();

Registry::set('jawbone_user_id',$jawbone_user_id);

$device_info = $_GET;
if (isset($_SESSION['device_info'])) {
  $device_info = json_decode($_SESSION['device_info'], true);
}
if (isset($device_info['device'])) {
  $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));
  if ($current_user) {
    switch($device_info['device']) {
      case 'android':
          $current_user->sensor = ( isset($device_info['sensor']) && $device_info['sensor'] == 1);
          $current_user->device = 'android';
          $current_user->save();

          if ($device_info['android_id'] != '') {
            $notifier = Notifier::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id, 'pushDevice' => 'android', 'pushId' => $device_info['android_id'])));
            if (!$notifier) {
              $notifier = new Notifier;
              $notifier->user_id = $current_user->id;
              $notifier->pushDevice = 'android';
              $notifier->pushId = $device_info['android_id'];
              $notifier->save();
            }
          }
        break;
    }
  }
  else {
    $_SESSION['device_info'] = json_encode($device_info);
  }

  /*(&& $current_user->pushId == '') {
    $current_user->pushDevice = 'android';
    $current_user->pushId = $_GET['android_id'];
    $current_user->save();*/
}

$action = Route::resolve();
if (is_numeric($jawbone_user_id) && !in_array($action['action'], array('signature','imagecapture'))) {
  $action = array('module' => 'main', 'action' => 'authenticate', 'params' => array());
}

$module_class = 'lib/modules/' . $action['module'] . '/actions/actions.class.php';
$module_name = ucfirst($action['module']) . 'Actions';
$action_name = 'execute' . ucfirst($action['action']);

require($module_class);
$module = new $module_name;
$module->action = $action['action'];
$module->module = $action['module'];
$module->{$action_name}($action['params']);
$module->render();

exit;
?>