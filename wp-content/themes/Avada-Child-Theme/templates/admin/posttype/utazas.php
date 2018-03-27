<table>
  <tr>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'ar'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Induló ár (Ft)', TD); ?></strong></label></p>
      <?php $ar_content = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$ar_content?>">
    </td>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'ar_akcios'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Induló akciós ár (Ft)', TD); ?></strong></label></p>
      <?php $ar_akcios = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$ar_akcios?>">
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
