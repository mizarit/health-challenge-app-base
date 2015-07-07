<script type="text/javascript">
  var jawbone_user_id = '<?php echo $user->xid; ?>';
  var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
  var isIos = <?php echo isset($_SESSION['isIos']) && $_SESSION['isIos']?'true':'false'; ?>;
  var isAndroid = <?php echo isset($_SESSION['isAndroid']) && $_SESSION['isAndroid']?'true':'false'; ?>;
  <?php $iOS = (bool)preg_match("/(iPad|iPhone|iPod)/si", $_SERVER['HTTP_USER_AGENT']); ?>
  function setTimespan() {
    <?php
    $ts = strtotime($challenge->startdate);
    $time = explode(':', $challenge->starttime);
    ?>
    setTimeout(setTimespan, 60000);
    var timespan = countdown(new Date(), new Date(<?php echo date('Y', $ts); ?>, <?php echo date('m', $ts); ?>, <?php echo date('d', $ts); ?>, <?php echo (int)$time[0]; ?>, <?php echo (int)$time[1]; ?>));//, units, max, digits);
    hours = timespan.hours.toString();
    days = timespan.days.toString();
    minutes = timespan.minutes.toString();
    $('day-1').innerHTML = days.length==1?'0':days[0];
    $('day-2').innerHTML = days.length==1?days[0]:days[1];
    $('hour-1').innerHTML = hours.length==1?'0':hours[0];
    $('hour-2').innerHTML = hours.length==1?hours[0]:hours[1];
    $('minute-1').innerHTML = minutes.length==1?'0':minutes[0];
    $('minute-2').innerHTML = minutes.length==1?minutes[0]:minutes[1];
  }

  Event.observe(window, 'load', function() {
    setTimespan();
  });

  setTimeout(function(){
    console.log('reload');
    window.location.href=window.location.href;
  }, 60000);


</script>
<div id="container">
  <?php require(dirname(__FILE__).'/_sidebarLeft.php'); ?>
  <?php require(dirname(__FILE__).'/_sidebarRight.php'); ?>
  <div id="main" onclick="clearSidebars();">
    <div id="overlay"></div>

    <div id="header">
      <h2 style="font-weight:bold;color:#fff;margin:0.1em 0 0 0;position:relative;padding:0;text-transform: uppercase;font-size:2em;">
        <?php if ($iOS) { ?>
          <button class="fa fa-reorder delayed" id="menu-button" class="delayed" onclick="toggleSidebar('sidebar-left');" style="position:absolute;left:-0.7em;color:#fff;top:0.1em;"></button>
          <button id="back-button" class="delayed" onclick="goPage(1);" style="display:none;background:url(/img/button-back.png);background-size:100% 100%;width: 0.9em; height: 1.6em;position:absolute;left:0.7em;top:0.4em;padding:0;margin:0;"></button>
          <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11,52))) { ?>
            <button class="fa fa-cog delayed" onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>';" style="margin:0;padding:0;position:absolute;right:0.3em;top:0.1em;color:#fff;width:auto;font-size:1em;"></button>
          <?php } ?>
        <?php } else { ?>
          <button id="back-button" class="delayed" onclick="goPage(1);" style="display:none;background:url(/img/button-back.png);background-size:100% 100%;width: 0.9em; height: 1.6em;position:absolute;left:0.4em;top:0.3em;padding:0;margin:0;"></button>
          <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11,52))) { ?>
            <button class="fa fa-cog delayed" onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>';" style="margin:0;padding:0;position:absolute;right:0.3em;top:0.1em;color:#fff;width:auto;font-size:1em;"></button>
          <?php } ?>
        <?php } ?>
        <?php echo $challenge->title; ?></h2>
    </div>
    <h4 class="subteaser"  id="subteaser1"><?php echo $challenge->teaser3; ?></h4>
    <div id="page-1" class="active">

      <div id="countdown">
        <h2>Nog</h2>
        <div style="width:30%; float:left;text-align:center;margin-right: 5%;">dagen<br>
          <div style="margin: 0 auto;width: 6em;padding: 0.5em 0;">
            <div class="counter" id="day-1" style="margin-right: 0.5em;"></div>
            <div class="counter" id="day-2"></div>
          </div>
        </div>
        <div style="width:30%; float:left;text-align:center;margin-right: 5%;">uur<br>
          <div style="margin: 0 auto;width: 6em;padding: 0.5em 0;">
            <div class="counter" id="hour-1" style="margin-right: 0.5em;"></div>
            <div class="counter" id="hour-2"></div>
          </div>
        </div>
        <div style="width:30%; float:left;text-align:center;">minuten<br>
          <div style="margin: 0 auto;width: 6em;padding: 0.5em 0;">
            <div class="counter" id="minute-1" style="margin-right: 0.5em;"></div>
            <div class="counter" id="minute-2"></div>
          </div></div>
        <div style="clear:both;"></div>
        <p><?php echo strftime('%A %e %B %H:%M', strtotime($challenge->startdate.' '.$challenge->starttime)); ?></p>
      </div>

      <h2>Jouw team</h2>
      <ul class="team-counters">
        <li style="background:#<?php echo $team->color; ?>"><?php echo $team->title; ?>
          <span style="font-size:0.6em;"><img src="/img/icon-person.png" alt=""><br><?php echo $team_data[$team->id]['users']; ?><?php if($team_data[$team->id]['max_users']>0&&$team_data[$team->id]['max_users']>=$team_data[$team->id]['users']){ echo '/'.$team_data[$team->id]['max_users']; } ?></span>
        </li>
      </ul>
      <ul id="ranking">
        <?php foreach($team_data[$team->id]['user_data'] as $k => $team_user) {
          ?>
          <li><div></div><div><span><?php echo $k + 1; ?></span>
              <img src="<?php echo $team_user['user']->image==''?'/img/silhouet.png':zeusImages::getPresentation('/'.trim($team_user['user']->image, '/'), array('width' => 140, 'height' => 140, 'resize_method' => zeusImages::RESIZE_CHOP)) ?>"><span><?php echo number_format($team_user['steps'], 0, ',', '.'); ?></span> stappen<br><?php echo $team_user['user']->firstName; ?> <?php echo $team_user['user']->lastName; ?></div><div></div></li>
        <?php } ?>
      </ul>

      <h2>Andere teams</h2>
      <ul class="team-counters">
        <?php foreach ($teams as $tteam) {
          if ($tteam->id == $team->id) continue;
          ?>
          <li style="background:#<?php echo $tteam->color; ?>"><?php echo $tteam->title; ?>
            <span style="font-size:0.6em;"><img src="/img/icon-person.png" alt=""><br><?php echo $team_data[$tteam->id]['users']; ?><?php if($team_data[$tteam->id]['max_users']>0&&$team_data[$tteam->id]['max_users']>=$team_data[$tteam->id]['users']){ echo '/'.$team_data[$tteam->id]['max_users']; } ?></span>
          </li>
        <?php } ?>
      </ul>


    </div>
  </div>
</div>
