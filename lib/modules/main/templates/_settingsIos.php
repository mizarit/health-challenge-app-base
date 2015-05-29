<div id="page-4">
  <div style="padding:0 1em;">
    <h2>Instellingen</h2>
  <input id="notifications" type="checkbox" onchange="iOS.setSetting('notifications', this.checked ? '1' : '0');$('notifications-vibrate').disabled=this.checked?'':'disabled';$('notifications-sound').disabled=this.checked?'':'disabled';">
  <label class="checkbox" for="notifications" style="margin-bottom:0.4em;"> Notificaties ontvangen</label><br>

  <div style="padding-left:2em;">
    <input id="notifications-vibrate" type="checkbox" checked="checked" onchange="iOS.setSetting('vibrate', this.checked ? '1' : '0');">
    <label class="checkbox" for="notifications-vibrate" style="margin-bottom:0.4em;"> Trillen toestaan</label><br>

    <input id="notifications-sound" type="checkbox" checked="checked" onchange="iOS.setSetting('sound', this.checked ? '1' : '0');">
    <label class="checkbox" for="notifications-sound" style="margin-bottom:0.4em;"> Geluid toestaan</label>
  </div>
    <?php
    $w = 22;
    $p = 70;

    ?>
    <h2>Mijn gegevens</h2>
    <label for="name" style="font-size:1.5em;">Wat is je naam?</label><br />
    <input type="text" value="<?php echo $user->firstName; ?>" name="name" id="name" style="width:96%;margin-bottom:0.5em;font-size:2em; padding: 0.2em;border-radius: 0.1em;"><br />

    <label for="height" style="font-size:1.5em;">Hoe lang ben je?</label><br />
    <input type="range" min="120" max="220" value="<?php echo (floatval($user->height) * 100); ?>" id="height" step="1" style="width:<?php echo $p; ?>%;margin:0 1em 0 0;">
    <span id="height-value"><?php echo $user->height; ?>m</span><br />
    <script type="text/javascript">
      Event.observe($('height'), 'input', function() {
        $('height-value').innerHTML = formatHeight(this.value / 100)+'m';
      });
    </script>
  </div>
  <p style="text-align:center;margin-top:2em;"><button type="button" id="save-btn" class="blue-btn">Opslaan</button></p>
</div>
<script type="text/javascript">
Event.observe(window, 'load', function() {
  $('notifications').checked = (iOS.getSetting('notifications') == "1");
  $('notifications-vibrate').checked = (iOS.getSetting('vibrate') == "1");
  $('notifications-sound').checked = (iOS.getSetting('sound') == "1");
  if (iOS.getSetting('notifications') == "0") {
    $('notifications-vibrate').disabled = 'disabled';
    $('notifications-sound').disabled = 'disabled';
  }
});
$('height-value').style.color = '#000';
Event.observe($('name'), 'keyup', function() {
  if(this.value.length < 3) {
    $('save-btn').addClassName('disabled');
  }
  else {
    $('save-btn').removeClassName('disabled');
  }
});

Event.observe($('save-btn'), 'click', function() {
  if(!$(this).hasClassName('disabled')) {
    new Ajax.Request('/main/settings?ju='+jawbone_user_id, {
      parameters: {
        name: $('name').value,
        height: $('height').value
      },
      onSuccess: function(transport) {
        iOS.showToast('Instellingen zijn opgeslagen.');
      }
    });
    goPage(1);
  }
});

</script>
<style type="text/css">
  input[type=range]::-webkit-slider-runnable-track {
    height: 1em !important;
    border-radius: 0.1em !important;
  }

  input[type=range]::-webkit-slider-thumb {
    height: 2em !important;
    width: 2em !important;
    margin-top: -0.5em !important;
  }
</style>