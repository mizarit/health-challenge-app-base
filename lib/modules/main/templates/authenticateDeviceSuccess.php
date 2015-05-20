<div id="main" style="padding-top:0;">
  <div id="authentication">
    <div id="header" style="position: relative;height: auto;">
      <h1 style="color:#fff;font-size:1.2em;margin:0;padding:0;font-weight:normal;">Kies je</h1>
      <h2 style="color:#fff;font-size:3em;margin:0;padding:0;line-height:1em;">Stappenteller</h2>
    </div>

    <div style="width:19em;margin:0 auto;margin-top:2em;">
      <?php if ($_SESSION['isAndroid']) { ?>
    <div id="device-native" style="cursor:pointer;float:left;margin-right:0.3em;"><img style="border-radius:0.4em;width:6em;" src="/img/devices/googlefit.png" alt="Google Fit"></div>
      <?php } ?>
      <?php if ($_SESSION['isIos']) { ?>
        <div id="device-native" style="cursor:pointer;float:left;margin-right:0.3em;"><img style="border-radius:0.4em;width:6em;" src="/img/devices/healthkit.png" alt="Apple Health Kit"></div>
      <?php } ?>
    <div id="device-jawbone-1" style="cursor:pointer;float:left;margin-right:0.3em;"><img style="border-radius:0.4em;width:6em;" src="/img/devices/jawbone1.png" alt="Jawbone Move"></div>
    <div id="device-jawbone-2" style="cursor:pointer;float:left;margin-right:0.3em;"><img style="border-radius:0.4em;width:6em;" src="/img/devices/jawbone2.png" alt="Jawbone Up"></div>
    </div>
    </div>
  </div>
<script type="text/javascript">
  $(body).style.background = '#25beef';
  Event.observe($('device-native'), 'click', function() {
    window.location.href='/main/authenticateDevice?sensorDevice=native';
  });
  Event.observe($('device-jawbone-1'), 'click', function() {
    window.location.href='/main/authenticateDevice?sensorDevice=jawbone';
  });
  Event.observe($('device-jawbone-2'), 'click', function() {
    window.location.href='/main/authenticateDevice?sensorDevice=jawbone';
  });
</script>