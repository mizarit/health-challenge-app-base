<script type="text/javascript">
  var local_data = <?php echo json_encode($local_data); ?>;
  var current_date = '<?php echo date('Y-m-d'); ?>';
  var total_cols = <?php echo $total_cols; ?>;
  var jawbone_user_id = '<?php echo $user->xid; ?>';
  var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
  var isIos = <?php echo isset($_SESSION['isIos'])?'true':'false'; ?>;
  var isAndroid = <?php echo isset($_SESSION['isAndroid'])?'true':'false'; ?>;
  <?php $iOS = (bool)preg_match("/(iPad|iPhone|iPod)/si", $_SERVER['HTTP_USER_AGENT']); ?>
</script>
<div id="container"<?php if($iOS) echo ' class="iOS"'; ?>">
<?php require(dirname(__FILE__).'/_sidebarLeft.php'); ?>
<?php require(dirname(__FILE__).'/_sidebarRight.php'); ?>
<div id="main" onclick="clearSidebars();">
  <div id="overlay"></div>

  <div id="header">
    <h2 style="font-weight:bold;color:#fff;margin:0.1em 0 0 0;position:relative;padding:0;text-transform: uppercase;font-size:2em;">
      <span id="chat-count" style="display:none;position:absolute;left:0.1em;top:0;">
        <i id="chat-count-value" class="fa fa-circle" style="position:absolute;left:0;top:0;font-size:1em;color:#c00;"></i>
        <span style="position:absolute;left:0.34em;top:0.1em;font-size:0.7em;font-weight:bold;"></span>
      </span>

      <?php if ($iOS) { ?>
        <button class="fa fa-reorder delayed" id="menu-button" class="delayed" onclick="toggleSidebar('sidebar-left');" style="position:absolute;left:-0.7em;color:#fff;top:0.1em;"></button>
        <button id="back-button" class="delayed" onclick="goPage(1);" style="display:none;background:url(/img/button-back.png);background-size:100% 100%;width: 0.9em; height: 1.6em;position:absolute;left:0.7em;top:0.4em;padding:0;margin:0;"></button>
        <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11,32,33,34,35))) { ?>
          <button class="fa fa-cog delayed" onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>';" style="margin:0;padding:0;position:absolute;right:0.3em;top:0.1em;color:#fff;width:auto;font-size:1em;"></button>
        <?php } ?>
      <?php } else { ?>
        <button id="back-button" class="delayed" onclick="goPage(1);" style="display:none;background:url(/img/button-back.png);background-size:100% 100%;width: 0.9em; height: 1.6em;position:absolute;left:0.4em;top:0.3em;padding:0;margin:0;"></button>
        <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11,32,33,34,35))) { ?>
          <button class="fa fa-cog delayed" onclick="window.location.href='/main/debug?ju=<?php echo $jawbone_user_id; ?>';" style="margin:0;padding:0;position:absolute;right:0.3em;top:0.1em;color:#fff;width:auto;font-size:1em;"></button>
        <?php } ?>

        <?php if (strpos($_SERVER['SERVER_NAME'], 'mizar') || in_array($user->id, array(9,10,11))) { ?>
          <span id="android-step-counter" style="position:absolute;left:1.1em;top:0.9em;font-size:1em;color:#fff;font-weight:bold;"></span>
          <i id="android-step" class="fa fa-circle" style="display:none;position:absolute;left:0.1em;top:1em;font-size:1em;color:#e7028b;"></i>

          <span id="ios-step-counter" style="position:absolute;left:1.1em;top:0.9em;font-size:1em;color:#fff;font-weight:bold;"></span>
          <i id="ios-step" class="fa fa-circle" style="display:none;position:absolute;left:0.1em;top:1em;font-size:1em;color:#e7028b;"></i>
        <?php } ?>
      <?php } ?>

      <?php echo $challenge->title; ?></h2>
    </div>
  <h4 class="subteaser"  id="subteaser1"><?php echo $challenge->teaser3; ?></h4>
  <div id="page-1" class="active">

    <div class="race-track" id="race-track-1">
      <div id="race-track-bar-1" onclick="goPage(3,1);"><div id="race-track-inner-bar-1" onclick="goPage(3,1);"></div><div class="closer"></div></div>
      <div id="race-track-bar-2" onclick="goPage(3,2);"><div id="race-track-inner-bar-2" onclick="goPage(3,2);"></div><div class="closer"></div></div>
      <div id="race-track-bar-3" onclick="goPage(3,3);"><div id="race-track-inner-bar-3" onclick="goPage(3,3);"></div><div class="closer"></div></div>
      <div id="race-track-bar-4" onclick="goPage(3,4);"><div id="race-track-inner-bar-4" onclick="goPage(3,4);"></div><div class="closer"></div></div>
      <div id="race-track-bar-5" onclick="goPage(3,5);"><div id="race-track-inner-bar-5" onclick="goPage(3,5);"></div><div class="closer"></div></div>
      <div id="race-track-label-1" onclick="goPage(3,1);"></div>
      <div id="race-track-label-2" onclick="goPage(3,2);"></div>
      <div id="race-track-label-3" onclick="goPage(3,3);"></div>
      <div id="race-track-label-4" onclick="goPage(3,4);"></div>
      <div id="race-track-label-5" onclick="goPage(3,5);"></div>
      <div class="startline" id="startline-1">start</div>
      <div class="checkerboard" id="checkerboard-1"></div>
    </div>

    <h2>Ranking</h2>
    <div class="cell-h-a"><span style="padding-left: 1.5em;">totaal</span></div>
    <div class="cell-h-b">vandaag</div>
    <div id="team-summary"></div>

    <div style="clear:both;"></div>
    <h2 style="margin-top:1em;">Jouw bijdrage</h2>
    <div class="cell-h-a"><span style="padding-left: 1.5em;">totaal</span></div>
    <div class="cell-h-b">vandaag</div>
    <div id="personal-summary"></div>

  </div>
  <div id="page-2">

    <div id="paging">
      <div id="page-prev">&laquo;</div>
      <?php if (strtotime($next_date) > time()) { ?>
        <div id="page-next" class="disabled">&raquo;</div>
      <?php } else { ?>
        <div id="page-next">&raquo;</div>
      <?php } ?>
      <div id="page-title"></div>
      <div style="clear:both;"></div>
    </div>

    <div id="graph" style="height:18em;">
      <div style="height:12em;width:100%;position:relative;">
        <?php
        $total_cols = max(1, $total_cols);
        $col_width = round(100 / $total_cols,3);

        //echo $col_width; 1.667
        //echo $total_cols; 60
        $x = 0;
        for ($col = 0; $col < $total_cols; $col++) { ?>
          <div id="graph-col-<?php echo $col; ?>" class="graph-bar graph-bar-flat" style="width:<?php echo round($col_width - 0.15,1) ; ?>%;left:<?php echo round($x,1); ?>%;"></div>
          <?php
          $x += $col_width;
        } ?>
      </div>
      <div style="height:6em;width:100%;position:relative;background:#f36518;color:#fff;">
        <div style="font-size:1em;position:absolute;top:0.3em;left:0.6%;color:#fff;"><?php echo $starttime; ?>:00</div>
        <div style="font-size:1em;position:absolute;top:0.3em;right:0.6%;color:#fff;"><?php echo $endtime; ?>:00</div>
        <p style="font-weight:bold;"><span id="cell-distance"></span> km<br>
          <span><span id="cell-steps"></span> stappen</span>
        </p>
      </div>
    </div>

    <div style="clear:both;"></div>
    <div style="display:<?php echo $user->adapter=='jawbone'?'block':'none'; ?>">
      <div class="cell-a"><div style="border-top:0;">Actieve tijd<br><span id="cell-active-time"></span></div></div>
      <div class="cell-b"><div style="border-top:0;">Totaal verbrand<br><span id="cell-cal-total"></span></div></div>
      <div class="cell-a"><div>Langst actief<br><span id="cell-longest-active-time"></span></div></div>
      <div class="cell-b"><div>Actief verbrand<br><span id="cell-cal-active"></span></div></div>
      <div class="cell-a"><div>Langst inactief<br><span id="cell-inactive-time"></span></div></div>
      <div class="cell-b"><div>Rustend verbrand<br><span id="cell-cal-rest"></span></div></div>
      <div style="clear:both;"></div>
    </div>

    <!--<div class="suggest"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis urna lacus, cursus vel purus eu, lacinia iaculis enim. Phasellus porta libero quis tortor venenatis, nec viverra dui venenatis. Nam augue dui, posuere ut dolor ac, venenatis finibus est. Vivamus tristique</p></div>-->

  </div>
  <div id="page-3">
    <div class="race-track" id="race-track-2">
      <div id="race-track-team-bar-1"><div id="race-track-team-inner-bar-1"></div><div class="closer"></div></div>
      <div id="race-track-team-label-1"></div>
      <div id="race-track-team-label-2"></div>
      <div class="startline">start</div>
      <div class="checkerboard"></div>
    </div>

    <h2>Team Ranking</h2>

    <ul id="ranking"></ul>

    <!--<div style="display: block;">
      <h3 id="group-users"></h3>
      <div id="stats-group">
        <div><span id="cell-group-steps"></span> stappen</div>
        <div><span id="cell-group-distance"></span>km</div>
        <div><span id="cell-group-active-time"></span> actief</div>
      </div>
      <div id="stats-subgroup">
        <div><span id="cell-my-steps"></span></div>
        <div><span id="cell-my-distance"></span></div>
        <div><span id="cell-my-active-time"></span></div>
      </div>
      <div id="stats-subsubgroup">
        <div><span id="cell-my-avg-steps"></span></div>
        <div><span id="cell-my-avg-distance"></span></div>
        <div><span id="cell-my-avg-active-time"></span></div>
      </div>
      <div style="clear:both;"></div>

      <div class="cell-a"><div><span id="cell-my-goal"></span></div></div>
      <div class="cell-b"><div><span id="cell-my-goal-perc"></span></div></div>
      <div class="cell-a"><div><span id="cell-group-goal"></span></div></div>
      <div class="cell-b"><div><span id="cell-group-goal-perc"></span></div></div>
      <div class="cell-e"></div>

      <div style="clear:both;"></div>
      <br>

      <div class="cell-a"><div><span id="cell-my-goal-week"></span> stappen</div></div>
      <div class="cell-b"><div><span id="cell-my-goal-week-perc"></span> behaald</div></div>
      <div class="cell-a"><div><span id="cell-group-goal-week"></span> stappen</div></div>
      <div class="cell-b"><div><span id="cell-group-goal-week-perc"></span> behaald</div></div>
      <div class="cell-e"></div>

      <div style="clear:both;"></div>
    </div>-->

  </div>
    <?php

   if(isset($_SESSION['isIos']) && $_SESSION['isIos']){
     require(dirname(__FILE__).'/_settingsIos.php');
   }
   if(isset($_SESSION['isAndroid']) && $_SESSION['isAndroid']){
     require(dirname(__FILE__).'/_settingsAndroid.php');
   }
?>

</div>
</div>
