<div id="main" style="padding-top:0;">
  <div id="authentication">
    <div id="header" style="position: relative;height: auto;">
      <h2 style="font-weight:bold;color:#fff;margin:0.1em 0 0 0;position:relative;padding:0;text-transform: uppercase;font-size:2em;"><?php echo $challenge->title; ?></h2>
    </div>
    <h4 class="subteaser"  id="subteaser1">We zijn er bijna..</h4>


    <form action="#" method="POST" id="name-form">
      <?php if(isset($errors)) { ?>
        <ul id="form-errors">
          <?php foreach ($errors as $error) { ?>
            <li><?php echo $error; ?></li>
          <?php } ?>
        </ul>
      <?php } ?>
      <?php
      $w = (isset($_SESSION['isIos']) && $_SESSION['isIos']) ? '24' : '26';
      $p = (isset($_SESSION['isIos']) && $_SESSION['isIos']) ? '68' : '74';

      ?>

      <div style="margin: 0 auto;width:<?php echo $w; ?>em;margin-top:1em;">
        <label for="name" style="color:#fff;font-size:2em;">Wat is je naam?</label>
        <input type="text" name="name" id="name" style="width:95%;font-size:3em; padding: 0.2em;border-radius: 0.1em;">
      </div>
      <div style="margin: 0 auto;width:<?php echo $w; ?>em;margin-top:1em;">
        <label for="height" style="color:#fff;font-size:2em;">Hoe lang ben je?</label>
        <input type="range" min="120" max="220" value="170" id="height" step="1" style="width:<?php echo $p; ?>%;margin:0 1em 0 0;">
        <span id="height-value">1.70m</span>
        <script type="text/javascript">
          Event.observe($('height'), 'input', function() {
            $('height-value').innerHTML = formatHeight(this.value / 100)+'m';
          });
        </script>


      </div>
      <p style="text-align:center;"><button type="button" id="authorize-btn" class="disabled blue-btn">Afronden<span></span></button></p>
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
<!--<button onclick="window.location.href=window.location.href;">refresh</button>-->
<style type="text/css">
  button {
    width:14em;
    font-size:1.3em;
  }

  button.disabled {
    background: #cecece !important;
  }

  <?php if(isset($_SESSION['isIos']) && $_SESSION['isIos']) { ?>
  input[type=range]::-webkit-slider-runnable-track {
    height: 1em !important;
    border-radius: 0.1em !important;
  }

  input[type=range]::-webkit-slider-thumb {
    height: 2em !important;
    width: 2em !important;
    margin-top: -0.5em !important;
  }

  <?php } ?>
</style>