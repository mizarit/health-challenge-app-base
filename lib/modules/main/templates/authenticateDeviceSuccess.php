<div id="main" style="padding-top:0;">
  <div id="authentication">
    <div id="header" style="position: relative;height: auto;">
      <h2 style="font-weight:bold;color:#fff;margin:0.1em 0 0 0;position:relative;padding:0;text-transform: uppercase;font-size:2em;"><?php echo $challenge->title; ?></h2>
    </div>
    <h4 class="subteaser"  id="subteaser1">Kies je stappenteller</h4>
  </div>
</div>

<ul id="device-list">
  <?php if (isset($_SESSION['isAndroid']) && $_SESSION['isAndroid']) { ?>
    <li id="device-native" class="device-native"><img src="/img/brand-googlefit.gif"></li>
  <?php } ?>
  <?php if (isset($_SESSION['isIos']) && $_SESSION['isIos']) { ?>
    <li id="device-native" class="device-native"><img src="/img/brand-healthkit.gif"></li>
  <?php } ?>

  <li id="device-jawbone" class="device-jawbone"><img src="/img/brand-jawbone.gif"></li>
  <li id="device-fitbit" class="device-fitbit"><img src="/img/brand-fitbit.gif"></li>
  <li class="device-none">Mijn merk zit er niet bij</li>
</ul>
<script type="text/javascript">
 // $(body).style.background = '#25beef';
  Event.observe($('device-native'), 'click', function() {
    window.location.href='/main/authenticateDevice?sensorDevice=native';
  });
  Event.observe($('device-jawbone'), 'click', function() {
    window.location.href='/main/authenticateDevice?sensorDevice=jawbone';
  });
  Event.observe($('device-fitbit-'), 'click', function() {
    window.location.href='/main/authenticateDevice?sensorDevice=fitbit';
  });
</script>