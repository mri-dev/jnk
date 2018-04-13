<table class="jnk">
  <tr>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'ar'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Meghirdetett ár (Ft)', TD); ?></strong></label></p>
      <?php $ar_content = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$ar_content?>">
    </td>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'ar_akcios'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Meghirdetett akciós ár (Ft)', TD); ?></strong></label></p>
      <?php $ar_akcios = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$ar_akcios?>">
    </td>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'travel_from'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Indulás - város', TD); ?></strong></label></p>
      <?php $travel_from = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$travel_from?>">
    </td>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'travel_to'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Érkezés - város', TD); ?></strong></label></p>
      <?php $travel_to = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$travel_to?>">
    </td>
  </tr>
  <tr>
    <td colspan="10">
      <?php $metakey = METAKEY_PREFIX . 'ar_magyarazat'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Meghirdetett ár magyarázó szövege (*)', TD); ?></strong></label></p>
      <?php $ar_magyarazat = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$ar_magyarazat?>">
    </td>
  </tr>
</table>
<table>
  <tr>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'kiemelt'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Kiemelt ajánlat', TD); ?></strong></label></p>
        <?php $kiemelt = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="checkbox" name="<?=$metakey?>" <?=($kiemelt=='1')?'checked="checked"':''?>>
    </td>
  </tr>
</table>
