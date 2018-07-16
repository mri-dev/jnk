<?php
  global $wpdb, $post;
?>
<table class="jnk">
  <tr>
    <td>
      <?php
        $metakey = METAKEY_PREFIX . 'photo_gallery_id';
        $selected_gallery_id = get_post_meta($post->ID, $metakey, true);
        $galleries = $wpdb->get_results($ig = "SELECT id, name FROM ".$wpdb->prefix."bwg_gallery WHERE published = 1 and autogallery_image_number != 0 ORDER BY `order` ASC, `name` ASC");
      ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Képgaléria kiválasztása', TD); ?></strong></label></p>
      <?php if ( count($galleries) == 0 ): ?>
        Nincs galéria létrehozva! <a href="/wp-admin/admin.php?page=galleries_bwg">Új galéria létrehozása >></a>
      <?php else: ?>
        <select class="" id="<?=$metakey?>" name="<?=$metakey?>">
          <option value="" selected="selected">-- nincs galéria kiválaszt: válasszon --</option>
          <option value="" disabled="disabled"></option>
          <?php foreach ($galleries as $gallery): ?>
          <option value="<?=$gallery->id?>" <?=($gallery->id == $selected_gallery_id)?'selected="selected"':''?>><?=$gallery->name?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </td>
  </tr>
</table>
