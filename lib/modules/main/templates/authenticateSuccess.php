<script type="text/javascript">
  var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
  var isIos = <?php echo isset($_SESSION['isIos'])?'true':'false'; ?>;
  var isAndroid = <?php echo isset($_SESSION['isAndroid'])?'true':'false'; ?>;
  <?php $iOS = (bool)preg_match("/(iPad|iPhone|iPod)/si", $_SERVER['HTTP_USER_AGENT']); ?>
</script>
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
      <div id="entrycode-inner">
        <input type="text" autocomplete="off" name="digits" id="digits" value="" type="number">
      </div>
    <p style="text-align:center;"><button type="button" id="authorize-btn" class="disabled blue-btn">Ga verder<span></span></button></p>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(body).style.background = '#25beef';
  Event.observe($('digits'), 'keyup', function(e) {
    $('authorize-btn').addClassName('disabled');
    if ($('digits').value.length == 6) {
      if (!isNaN($('digits').value)) {
        $('authorize-btn').removeClassName('disabled');
      }
      $('digits').blur();
    }
  });

Event.observe($('authorize-btn'), 'click', function() {
  if(!$(this).hasClassName('disabled')) {
    $('entrycode-form').submit();
    //window.location.href='<?php //echo $oauth_url; ?>';
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