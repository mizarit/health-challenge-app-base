<link href="/css/demo.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript">
  var isIos = <?php echo isset($_SESSION['isIos'])?'true':'false'; ?>;
  var isAndroid = <?php echo isset($_SESSION['isAndroid'])?'true':'false'; ?>;
  function moreInfo()
  {
    $('more-info').hide();
    $$('.more-info').each(function(s,i) {
      $(s).style.display = 'table-row';
    });
  }
  
  Event.observe(window, 'load', function() {
    if(isIos) {
      var hasSound = iOS.getSetting('sound')=="1";
      var hasVibrate = iOS.getSetting('vibrate')=="1";
      var hasNotifications = iOS.getSetting('notifications')=="1";
      
      $('btn-iOS-sound').style.background = hasSound ? '#f79035' : '#cccccc';
      $('btn-iOS-vibrate').style.background = hasVibrate ? '#f79035' : '#cccccc';
      $('btn-iOS-notifications').style.background = hasNotifications ? '#f79035' : '#cccccc';
    }
    else if(isAndroid) {
      var hasSound = Android.getSetting('sound')=="1";
      var hasVibrate = Android.getSetting('vibrate')=="1";
      var hasNotifications = Android.getSetting('notifications')=="1";
      
      $('btn-sound').style.background = hasSound ? '#f79035' : '#cccccc';
      $('btn-vibrate').style.background = hasVibrate ? '#f79035' : '#cccccc';
      $('btn-notifications').style.background = hasNotifications ? '#f79035' : '#cccccc';
    }
    else {
      hasSound = hasVibrate = hasNotitications = false;
    }
  });
</script>
<div id="main">
  <h2 style="color:#fff;font-weight:bold;margin:0.1em 0;position:relative;">Health Challenge Debugger</h2>
  <div id="debug-container">
    <div id="overlay" style="display:none;"></div>
    <?php
    $user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));
    ?>
    <table>
      <tr>
        <th>User</th>
        <td>
          <select name="jawbone-user" id="jawbone-user" onchange="window.location.href='/main/debug?ju='+this.value;">
            <?php
            $users = User::model()->findAll();

            foreach ($users as $cuser) {
              $team = TeamUser::model()->findByAttributes(new Criteria(array('user_id' => $cuser->id)));

              $name = $cuser->firstName.' '.$cuser->lastName;
              if ($team) {
                $name .= ' ('.$team->team->title.', '.$team->team->challenge->title.')';
              }
              ?>
              <option <?php if($jawbone_user_id == $cuser->xid) echo ' selected="selected"'; ?> value="<?php echo $cuser->xid; ?>"><?php echo $name ?></option>
            <?php } ?>
          </select>
      </tr>
      <tr id="more-info">
        <th><button onclick="window.location.href='/main/authenticate';" style="width:8em;font-size:1em;background:#2f7ae1;margin-right:0;">autoriseren</button></th>
        <td style="text-align:right;"><button onclick="moreInfo();" style="width:8em;font-size:1em;background:#2f7ae1;margin-right:0;">meer informatie</button></td>
      </tr>
      <tr class="more-info">
        <th>Access token</th>
        <td><?php echo $user->accessToken; ?></td>
      </tr>
      <tr class="more-info">
        <th>Refresh token</th>
        <td><?php echo $user->refreshToken; ?></td>
      </tr>
      <tr class="more-info">
        <th>Keys in keystore</th>
        <td><?php echo count($users); ?></td>
      </tr>
      <tr class="more-info">
        <th>Client ID</th>
        <td><?php echo Registry::get('client_id'); ?></td>
      </tr>
      <tr class="more-info">
        <th>App secret</th>
        <td><?php echo Registry::get('app_secret'); ?></td>
      </tr>
    </table>
 
    <button style="width:48%;background:#2f7ae1;" onclick="$('overlay').style.display='block';$('overlay').addClassName('popup');window.location.href=window.location.href;">Refresh</button>
    <button style="width:49%;background:#f79035;" onclick="$('overlay').style.display='block';$('overlay').addClassName('popup');window.location.href='/main/index?ju=<?php echo $jawbone_user_id; ?>';">Mobiele weergave</button>
    <hr>
    <h2>Android test suite</h2>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="window.Android.beep();">Sound</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="window.Android.vibrate(500);">Vibrate</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="window.Android.showToast('Dit is een toast bericht!');">Toast</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> id="btn-sound" onclick="handleSound();">Sound</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> id="btn-vibrate" onclick="handleVibrate();">Vibrate</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> id="btn-notifications" onclick="handleNotifications();">Notificaties</button><br>

    <form action="#" method="post" id="frm">
    <textarea <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;width:97%;margin: 0 1%;"'; ?> name="msg" id="msg" cols="40" rows="4" style="font-size:1em;width:97%;margin: 0 1%;"><?php echo isset($_POST['msg'])?$_POST['msg']:''; ?></textarea><br>
    <input type="hidden" name="msgtype" id="msgtype" value="message">
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> type="button" onclick="$('frm').submit();">Bericht verzenden</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> type="button" onclick="$('msgtype').value='payload';$('frm').submit();">Payload verzenden</button>
    <button <?php if(!isset($_SESSION['isAndroid']) || !$_SESSION['isAndroid']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> type="button" onclick="alert(window.Android.getPayload());">Payload<br>ophalen</button>
    </form>
    <?php if (isset($push_result)) echo $push_result; ?>
    <hr>
    <h2>iOS test suite</h2>
    <button <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="window.iOS.beep();">Sound</button>
    <button <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="window.iOS.vibrate(500);">Vibrate</button>
    <button <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="window.iOS.showToast('Dit is een toast bericht!');">Toast</button>
    <button <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> id="btn-iOS-sound" onclick="handleiOSSound();">Sound</button>
    <button <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> id="btn-iOS-vibrate" onclick="handleiOSVibrate();">Vibrate</button>
    <button <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> id="btn-iOS-notifications" onclick="handleiOSNotifications();">Notificaties</button><br>
    
    <form action="#" method="post" id="frm-iOS">
    <textarea  <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;width:97%;margin: 0 1%;"'; ?> name="msg-iOS" id="msg-iOS" cols="40" rows="4" style="width:97%;margin: 0 1%;"><?php echo isset($_POST['msg-iOS'])?$_POST['msg-iOS']:''; ?></textarea><br>
    <input type="hidden" name="msgtype-iOS" id="msgtype-iOS" value="message">
    <button type="button" <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="$('frm-iOS').submit();">Bericht verzenden</button>
    <button type="button" <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="$('msgtype-iOS').value='payload';$('iOS').submit();">Payload verzenden</button>
    <button type="button" <?php if(!isset($_SESSION['isIos']) || !$_SESSION['isIos']) echo ' disabled="disabled" style="background:#cccccc;"'; ?> onclick="alert(window.iOS.getPayload());">Payload<br>ophalen</button>
    <hr>
    <h2>API test suite</h2>
    <button onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>&api=moves';">API Moves</button>
    <button onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>&api=user';">API User</button>
    <button onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>&api=body_events';">API Body</button>
    <button onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>&api=goals';">API Goals</button>
    <button onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>&api=trends';">API Trends</button>
    <?php
    if (isset($_GET['api'])) {
      echo '<h3>API '.ucfirst($_GET['api']).'</h3>';
      $api = $_GET['api'] == 'user' ? '' : '/'.$_GET['api'];
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_URL, 'https://jawbone.com/nudge/api/v.1.1/users/@me'.$api);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer ".$user->accessToken
      ));
      $output = json_decode(curl_exec($curl));
      if (isset($output->meta->error_type)) {
        echo '<p>Error on API call';
        if($output->meta->error_type == 'authorization_error') { ?>
          <p>Not all permission scopes are allowed. Please authorize again.</p>
          <button onclick="window.location.href='<?php echo $oauth_url; ?>';">Autoriseren</button>
        <?php
        }
      }
      else {
        if ($_GET['api'] == 'moves') {
          foreach ($output->data->items as $item) {
            echo '<h4>'.date('Y-m-d', $item->time_completed).' ('.$item->details->steps.' stappen )</h4>';
            echo '<p>';
            echo round($item->details->bmr_day).' cal. in rust verbrand<br>'.PHP_EOL;
            echo round($item->details->calories).' cal. actief verbrand<br>'.PHP_EOL;
            echo round($item->details->calories)+round($item->details->bmr_day).' cal. totaal verbrand<br>'.PHP_EOL;
            echo round($item->details->km).' km gelopen<br>'.PHP_EOL;
            echo format_time($item->details->longest_active).' langste actief<br>'.PHP_EOL;
            echo format_time($item->details->longest_idle).' langste inactief<br>'.PHP_EOL;

            echo '</p>';
            echo '<ul>';
            $items = array();
            $data = array(
              '00' => 0,
              '01' => 0,
              '02' => 0,
              '03' => 0,
              '04' => 0,
              '05' => 0,
              '06' => 0,
              '07' => 0,
              '08' => 0,
              '09' => 0,
              '10' => 0,
              '11' => 0,
              '12' => 0,
              '13' => 0,
              '14' => 0,
              '15' => 0,
              '16' => 0,
              '17' => 0,
              '18' => 0,
              '19' => 0,
              '20' => 0,
              '21' => 0,
              '22' => 0,
              '23' => 0,
            );
            foreach ($item->details->hourly_totals as $time => $hour) {
              $items[substr($time,8,2)] = '<li>'.substr($time,8,2).':00 => '.$hour->steps.'</li>';
              $data[substr($time,8,2)] = $hour->steps;

            }
            ksort($items);
            echo implode("\n", $items);
            echo '</ul>';
            ?>

            <div style="height:135px;width:240px;position:relative;border:#c00 1px solid;border-radius:3px;padding:10px;margin-bottom:40px;">
              <?php
              $x = 0;
              $vmax = max($data);
              $factor = 150 / $vmax;
              foreach ($data as $hour => $steps) {
                $h = $steps * $factor;
                ?>
                <div style="width:9px;position:absolute;bottom:0;background:#c00;left:<?php echo $x; ?>px;height:<?php echo $h; ?>px;"></div>
                <?php
                $x += 10;
              }
              ?>
              <div style="transform:rotate(90deg);transform-origin:left top 0;font-size:10px;position:absolute;bottom:-20px;left:15px;">0:00</div>
              <div style="transform:rotate(90deg);transform-origin:left top 0;font-size:10px;position:absolute;bottom:-20px;left:120px;">12:00</div>
              <div style="transform:rotate(90deg);transform-origin:left top 0;font-size:10px;position:absolute;bottom:-20px;left:260px;">24:00</div>
            </div>
          <?php

          }
        }
        else if($_GET['api'] == 'user') {
          echo '<h4>'.$output->data->first.' '.$output->data->last.'</h4>';
          echo '<p>'.$output->data->height.'m, '.$output->data->weight.'kg</p>';
        }
        else if($_GET['api'] == 'body_events') {
          echo '<ul>';
          foreach ($output->data->items as $item) {
            echo '<li>'.date('Y-m-d', strtotime(substr($item->date,0,8))).' => ';
            echo $item->weight.'kg</li>';
          }
          echo '</ul>';
        }

        echo '<hr><h4>Ruwe output van API</h4>';
        object_dump($output);
      }
      curl_close($curl);

    } ?>
  </div>
</div>