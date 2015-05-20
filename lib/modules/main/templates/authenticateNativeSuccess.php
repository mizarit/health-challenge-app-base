<div id="main" style="padding-top:0;">
  <div id="authentication">
    <div id="header" style="position: relative;height: auto;">
      <h1 style="color:#fff;font-size:1.2em;margin:0;padding:0;font-weight:normal;">We zijn er bijna..</h1>
      <h2 style="color:#fff;font-size:3em;margin:0;padding:0;line-height:1em;">Wat is je naam?</h2>
    </div>
    <form action="#" method="POST" id="name-form">
      <?php if(isset($errors)) { ?>
        <ul id="form-errors">
          <?php foreach ($errors as $error) { ?>
            <li><?php echo $error; ?></li>
          <?php } ?>
        </ul>
      <?php } ?>
<div style="margin: 0 auto;width:26em;margin-top:1em;">
  <input type="text" name="name" id="name" style="width:95%;font-size:3em; padding: 0.2em;border-radius: 0.1em;">
</div>
      <h2 style="color:#fff;font-size:1em;margin:1em 0 0 0;padding:0;line-height:1em;">En hoe lang ben je?</h2>
      <div style="margin: 0 auto;margin-top:0.3em;width:5em;font-size:3em;padding-left:1em;">
  <input type="text" value="170" name="height" id="height" style="width:50%;font-size:1em; padding: 0.2em;border-radius: 0.1em;"> <span style="color:#fff;">cm</span>
</div>
      <p style="text-align:center;"><button type="button" id="authorize-btn" class="disabled blue-btn">Ga verder<span></span></button></p>
    </form>
    </div>
  </div>
<script type="text/javascript">
  $(body).style.background = '#25beef';
  Event.observe($('authorize-btn'), 'click', function() {
    if(!$(this).hasClassName('disabled')) {
      $('name-form').submit();
      //window.location.href='<?php //echo $oauth_url; ?>';
    }
  });
  Event.observe($('name'), 'keyup', function() {
    if($(this).value.length >= 2) {
      $('authorize-btn').removeClassName('disabled');
    }
    else {
      $('authorize-btn').addClassName('disabled');
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