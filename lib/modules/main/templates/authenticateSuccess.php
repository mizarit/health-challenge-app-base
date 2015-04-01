<div id="main" style="padding-top:0;">
  <div id="authentication">
    <div id="header" style="position: relative;height: auto;">
    <h1 style="color:#fff;font-size:1.2em;margin:0;padding:0;font-weight:normal;">Welkom bij de</h1>
    <h2 style="color:#fff;font-size:3em;margin:0;padding:0;line-height:1em;">Health Challenge</h2>
    </div>
    <p style="color:#fff;font-size:1.5em;margin:0.8em 0;padding:0;text-align:center;">Voer uw persoonlijke code in</p>
    <form action="#" method="POST" id="entrycode-form">
    <?php if(isset($errors)) { ?>
    <ul id="form-errors">
    <?php foreach ($errors as $error) { ?>
      <li><?php echo $error; ?></li>
    <?php } ?>
    </ul>
    <?php } ?>
    <div id="entrycode">
      <input name="digit-1" id="digit-1" value="" autocomplete="off" type="number" step="1">
      <input name="digit-2" id="digit-2" value="" autocomplete="off" type="number" step="1">
      <input name="digit-3" id="digit-3" value="" autocomplete="off" type="number" step="1">
      <input name="digit-4" id="digit-4" value="" autocomplete="off" type="number" step="1">
      <input name="digit-5" id="digit-5" value="" autocomplete="off" type="number" step="1">
      <input name="digit-6" id="digit-6" value="" autocomplete="off" type="number" step="1">
    </div>
    <p style="text-align:center;"><button type="button" id="authorize-btn" class="disabled blue-btn">Ga verder<span></span></button></p>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(body).style.background = '#25beef';
$$('#entrycode input').each(function(s,i) {
  Event.observe($(s), 'keyup', function(e) {
    
    x = $(this).id.substr(6);
    
    if(e.keyCode == 8 && x > 1) {
      x--;
      for (y = 6; y > x;y--) {
        $('digit-'+y).value = '';
      }
      $('digit-'+x).focus();
      $('authorize-btn').addClassName('disabled');
      return;
    }
    if(isNaN($(this).value)) {
      $(this).value = '';
    }
    if(!isNaN($(this).value)) {
      if($(this).value.length > 0) {
        $(this).value = $(this).value.substr(0,1);
        x++;
        if (x < 7) {
          $('digit-'+x).focus();
          $('authorize-btn').addClassName('disabled');
        }
        else {
          $('authorize-btn').removeClassName('disabled');
        }
      }
    }
  });
});

Event.observe($('authorize-btn'), 'click', function() {
  if(!$(this).hasClassName('disabled')) {
    $('entrycode-form').submit();
    //window.location.href='<?php echo $oauth_url; ?>';
  }
});
</script>
<style type="text/css">
button {
  width:14em;
  font-size:1.3em;
}

button.disabled {
  background: #cecece !important;
}
</style>