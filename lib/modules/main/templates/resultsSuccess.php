<script type="text/javascript">
  var jawbone_user_id = '<?php echo $user->xid; ?>';
  var challenge_start = new Date('<?php echo $challenge->startdate.' '.$challenge->starttime; ?>');
  var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
  <?php $iOS = (bool)preg_match("/(iPad|iPhone|iPod)/si", $_SERVER['HTTP_USER_AGENT']); ?>
  function setTimespan() {
    setTimeout(setTimespan, 60000);
    var timespan = countdown(new Date(), challenge_start);//, units, max, digits);
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
    window.location.href=window.location.href;
  }, 60*60*1000);


</script>
<div id="container" class="results">
  <?php require(dirname(__FILE__).'/_sidebarLeft.php'); ?>
  <?php require(dirname(__FILE__).'/_sidebarRight.php'); ?>
  <div id="main" onclick="clearSidebars();">
    <div id="overlay"></div>

    <div id="header">
      <h2 style="font-weight:bold;color:#fff;margin:0.1em 0 0 0;position:relative;padding:0;text-transform: uppercase;font-size:2em;">

        <?php if ($iOS) { ?>
          <button class="fa fa-reorder delayed" id="menu-button" class="delayed" onclick="toggleSidebar('sidebar-left');" style="position:absolute;left:-0.7em;color:#fff;top:0.1em;"></button>
          <button id="back-button" class="delayed" onclick="goPage(1);" style="display:none;background:url(/img/button-back.png);background-size:100% 100%;width: 0.9em; height: 1.6em;position:absolute;left:0.7em;top:0.4em;padding:0;margin:0;"></button>
          <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11))) { ?>
            <button class="fa fa-cog delayed" onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>';" style="margin:0;padding:0;position:absolute;right:0.3em;top:0.1em;color:#fff;width:auto;font-size:1em;"></button>
          <?php } ?>
        <?php } else { ?>
          <button id="back-button" class="delayed" onclick="goPage(1);" style="display:none;background:url(/img/button-back.png);background-size:100% 100%;width: 0.9em; height: 1.6em;position:absolute;left:0.4em;top:0.3em;padding:0;margin:0;"></button>
          <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11))) { ?>
            <button class="fa fa-cog delayed" onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>';" style="margin:0;padding:0;position:absolute;right:0.3em;top:0.1em;color:#fff;width:auto;font-size:1em;"></button>
          <?php } ?>
        <?php } ?>
        <?php echo $challenge->title; ?></h2>
    </div>
    <h4 class="subteaser"  id="subteaser1"><?php echo $challenge->teaser3; ?></h4>
    <div id="page-1" class="active" style="background:transparent;height:100%;">

      <div id="trophy"><img src="/img/trophy-winner.png" alt=""></div>
      <!--
            <div id="trophy">
              <img src="/img/trophy-loser.png" alt="">
              <span>3</span>
            </div>-->
      <?php
      $first = array_shift($ranking);
      foreach ($team_data as $tteam) {
        if($tteam['id']==$first) {
          ?>
          <ul class="team-counters">
            <li style="background:#<?php echo $tteam['color']; ?>;">
              <?php echo $tteam['title']; ?>
              <span class="bullet">1</span>
              <span class="stats"><?php echo number_format($tteam['total']['distance'], 0, ',', '.'); ?>km / <?php echo number_format($tteam['total']['steps'], 0, ',', '.'); ?> stappen</span>
            </li>
          </ul>
        <?php
        }
      }
      ?>


      <div style="background:#00a0e2;height:100%;padding: 2em 0;margin-top:1em;">

        <ul class="team-counters">
          <?php
          $p = 2;
          foreach ($ranking as $rank) {
            foreach ($team_data as $tteam) {
              if ($tteam['id'] == $rank) {
                ?>
                <ul class="team-counters">
                  <li style="background:#<?php echo $tteam['color']; ?>;">
                    <?php echo $tteam['title']; ?>
                    <span class="bullet"><?php echo $p; ?></span>
                  <span class="stats"><?php echo number_format($tteam['total']['distance'], 0, ',', '.'); ?>
                    km / <?php echo number_format($tteam['total']['steps'], 0, ',', '.'); ?> stappen</span>
                  </li>
                </ul>
              <?php
              }
            }
            $p++;
          }
          ?>
        </ul>

      </div>

    </div>
  </div>
</div>
