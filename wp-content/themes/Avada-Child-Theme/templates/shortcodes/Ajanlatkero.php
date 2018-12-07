<a name="_form"></a>
<form id="mailsend" action="" method="post">
  <input type="hidden" name="formtype" value="<?=$tipus?>">
  <div class="group-holder requester-holder" style="width: <?=$width?>%;">
      <div class="flxtbl">
        <div class="name">
          <div class="form-input-holder">
            <input type="text" id="name" name="name" class="form-control" placeholder="<?=__('Az Ön neve', 'jnk')?> *" value="">
          </div>
        </div>
        <div class="email">
          <div class="form-input-holder">
            <input type="text" id="email" name="email" class="form-control" placeholder="<?=__('E-mail címe', 'jnk')?> *" value="">
          </div>
        </div>
        <div class="subject">
          <div class="form-input-holder">
            <input type="text" id="subject" name="subject" class="form-control" placeholder="<?=__('Téma', 'jnk')?> *" value="">
          </div>
        </div>
        <div class="uzenet">
          <div class="form-input-holder">
            <textarea name="uzenet" id="uzenet" class="form-control" placeholder="<?=__('Üzenet', 'jnk')?> *"></textarea>
          </div>
        </div>
        <div class="recaptcha">
          <div class="g-recaptcha" data-sitekey="<?=CAPTCHA_SITE_KEY?>"></div>
        </div>
      </div>
  </div>

  <div class="btns" style="width: <?=$width?>%;">
    <div id="mail-msg" style="display: none;">
      <div class="alert"></div>
    </div>
    <button type="button" id="mail-sending-btn" onclick="uzenetKuldes();"><?php echo $button_text; ?></button>
  </div>

</form>


<script type="text/javascript">
var mail_sending_progress = 0;
var mail_sended = 0;
function uzenetKuldes()
{
  if(mail_sending_progress == 0 && mail_sended == 0){
    jQuery('#mail-sending-btn').html('<?php echo $whatisit; ?> <?=__('küldés folyamatban', 'jnk')?>', 'Avada'); ?> <i class="fa fa-spinner fa-spin"></i>').addClass('in-progress');
    jQuery('#mailsend .missing').removeClass('missing');

    mail_sending_progress = 1;
    var mailparam  = jQuery('#mailsend').serializeArray();
    jQuery.post(
      '<?php echo admin_url('admin-ajax.php'); ?>?action=contact_form',
      mailparam,
      function(data){
        var resp = jQuery.parseJSON(data);
        console.log(resp);
        if(resp.error == 0) {
          mail_sended = 1;
          jQuery('#mail-sending-btn').html('<?php echo $whatisit; ?> <?=__('elküldve', 'jnk')?> <i class="fa fa-check-circle"></i>').removeClass('in-progress').addClass('sended');
        } else {
          jQuery('#mail-sending-btn').html('<?php echo $button_text; ?>').removeClass('in-progress');
          jQuery('#mail-msg').show();
          jQuery('#mail-msg .alert').html(resp.msg).addClass('alert-danger');
          mail_sending_progress = 0;
          if(resp.missing != 0) {
            jQuery.each(resp.missing_elements, function(i,e){
              jQuery('#mailsend #'+e).addClass('missing');
            });
          }
        }
      }
    );
  }
}
</script>
