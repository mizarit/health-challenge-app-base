<div id="page-4">
  <div style="padding:0 1em 5em 1em;">
  <?php
  $w = 22;
  $p = 70;

  ?>

    <?php
    $image  = '/img/silhouet-large.png';
    if ($user->image != '' && file_exists(getcwd().'/'.$user->image)) {
      $image = '/'.$user->image;
    }
    ?>
    <p style="text-align:center;">
      <input type="hidden" name="imagefile" id="imagefile">
    <img src="<?php echo $image; ?>" id="user-image" onclick="Android.attachFileInput();">
    </p>
    <h2 style="text-align:left;margin-top:1em;">Je persoonlijke gegevens</h2>
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

  <h2 style="text-align:left;margin-top:1em;">Notificaties</h2>
  <input id="notifications" type="checkbox" onchange="Android.setSetting('notifications', this.checked ? '1' : '0');$('notifications-vibrate').disabled=this.checked?'':'disabled';$('notifications-sound').disabled=this.checked?'':'disabled';">
  <label class="checkbox" for="notifications" style="margin-bottom:0.4em;"> Notificaties ontvangen</label><br>

  <div style="padding-left:2em;">
    <input id="notifications-vibrate" type="checkbox" checked="checked" onchange="Android.setSetting('vibrate', this.checked ? '1' : '0');">
    <label class="checkbox" for="notifications-vibrate" style="margin-bottom:0.4em;"> Trillen toestaan</label><br>

    <input id="notifications-sound" type="checkbox" checked="checked" onchange="Android.setSetting('sound', this.checked ? '1' : '0');">
    <label class="checkbox" for="notifications-sound" style="margin-bottom:0.4em;"> Geluid toestaan</label>
  </div>
  </div>
</div>
<button type="button" id="save-btn" class="orange-btn">Opslaan</button>
<script type="text/javascript">
Event.observe(window, 'load', function() {
  if( typeof(Android) != 'undefined') {
    $('notifications').checked = (Android.getSetting('notifications') == "1");
    $('notifications-vibrate').checked = (Android.getSetting('vibrate') == "1");
    $('notifications-sound').checked = (Android.getSetting('sound') == "1");
    if (Android.getSetting('notifications') == "0") {
      $('notifications-vibrate').disabled = 'disabled';
      $('notifications-sound').disabled = 'disabled';
    }
  }
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
          height: $('height').value,
          imagefile: $('imagefile').value
        },
        onSuccess: function(transport) {
          console.log(transport.responseText);
          Android.showToast('Instellingen zijn opgeslagen.');
        }
      });
      goPage(1);
    }
  });
});

function imageSelected(image)
{
  $('user-image').src = 'data:image/jpg;base64,'+image;
  $('imagefile').value = image;
  var img = new Element('img', { src: 'data:image/jpg;base64,'+image });
  $('page-4').insert(img);
}

</script>
