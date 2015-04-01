<script type="text/javascript">
  var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
  <?php $iOS = (bool)preg_match("/(iPad|iPhone|iPod)/si", $_SERVER['HTTP_USER_AGENT']); ?>
</script>
<div id="container">
  <?php require(dirname(__FILE__).'/_sidebarLeft.php'); ?>
  <?php require(dirname(__FILE__).'/_sidebarRight.php'); ?>
  <div id="main" style="padding-top:0;">
    <div style="text-align: center;">

      <div id="header" style="position: relative;height: auto;">
        <h2 style="color:#fff;font-weight:bold;margin:0.1em 0 0 0 ;position:relative;text-transform: uppercase;font-size:2em;">

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
        <p style="color:#fff;font-size:1.2em;">Hi <?php echo $user->firstName; ?>, fantastisch dat je meedoet met de <?php echo $challenge->title; ?>.</p>
        <p style="color:#fff;font-size:1.2em;">Je maakt onderdeel uit van team:</p>
        <h3 style="background:#f36518;font-size:2em;height:2em;line-height:2em;color:#fff;text-align: center;margin-bottom:0.5em;"><?php echo $team->title; ?></h3>

      </div>

      <div style="background: #fff;position:relative;height:16em;width:100%;background-image:url(<?php echo zeusImages::getPresentation($challenge->image, array('height' => 590, 'resize_method' => zeusImages::RESIZE_CHOP)); ?>);background-position: center top;background-size: contain;background-repeat: no-repeat;">
        <div style="background: #000; opacity: 0.2;width:100%;height: 8em;position:absolute;top:7.5em;"></div>
        <div style="position:absolute;top:9em;width:100%;">
          <p style="font-size:1em;margin:0;padding:0;color:#fff;text-align:center;text-transform: uppercase;"><?php echo $challenge->teaser1; ?></p>
          <p style="font-size:1.5em;margin:0;padding:0;color:#fff;text-align:center;text-transform: uppercase;font-weight:bold;"><?php echo $challenge->teaser2; ?></p>
        </div>
      </div>

      <p style="text-align:center;"><button class="blue-btn" onclick="window.location.href='/main/index';">Ga verder<span></span></button></p>
    </div>
  </div>
</div>
      