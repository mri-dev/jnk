<?php
  global $wpdb, $post;
  $programok = get_posts(array(
    'post_type' => 'programok',
    'posts_per_page' => -1,
    'orderby' => 'name',
    'order' => 'ASC'
  ));
  $ajanlatok = get_posts(array(
    'post_type' => UTAZAS_SLUG,
    'posts_per_page' => -1,
    'orderby' => 'name',
    'order' => 'ASC',
    'post__not_in' => array($post->ID)
  ));
?>
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
    <td colspan="2">
      <?php $metakey = METAKEY_PREFIX . 'ar_magyarazat'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Meghirdetett ár magyarázó szövege (*)', TD); ?></strong></label></p>
      <?php $ar_magyarazat = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$ar_magyarazat?>">
    </td>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'max_befogadas'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Befogadóképesség - Csoportos utazásnál', TD); ?></strong></label></p>
      <?php $max_befogadas = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="text" name="<?=$metakey?>" value="<?=$max_befogadas?>" placeholder="pl.: 1 - 20 fő">
    </td>
    <td colspan="2">
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
  <tr>
    <td colspan="2">
      <p><label class="post-attributes-label" for=""><strong><?php echo __('Program ajánlók', TD); ?></strong></label></p>
      <div class="categorydiv">
        <div class="tabs-panel">
          <ul class="categorychecklist ">
            <?php
            $metakey = METAKEY_PREFIX . 'programok';
            $program_ids = explode(",",get_post_meta($post->ID, $metakey, true));
            ?>
            <?php foreach ($programok as $program): ?>
            <li>
              <label for="program_aj_<?=$program->ID?>"><input id="program_aj_<?=$program->ID?>" type="checkbox" <?=(in_array($program->ID, $program_ids))?'checked="checked"':''?> name="programok[]" value="<?=$program->ID?>"> <?=$program->post_title?></label>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </td>
    <td colspan="2">
      <p><label class="post-attributes-label" for=""><strong><?php echo __('Ajánlott utazások', TD); ?></strong></label></p>
      <div class="categorydiv">
        <div class="tabs-panel">
          <ul class="categorychecklist ">
          <?php
          $metakey = METAKEY_PREFIX . 'ajanlatok';
          $ajanlat_ids = explode(",",get_post_meta($post->ID, $metakey, true));
          ?>
          <?php foreach ($ajanlatok as $ajanlat): ?>
          <li>
            <label for="ajanlat_aj_<?=$ajanlat->ID?>"><input id="ajanlat_aj_<?=$ajanlat->ID?>" type="checkbox" <?=(in_array($ajanlat->ID, $ajanlat_ids))?'checked="checked"':''?> name="ajanlatok[]" value="<?=$ajanlat->ID?>"> <?=$ajanlat->post_title?></label>
          </li>
          <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </td>
  </tr>
</table>
<table class="jnk">
  <tr>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'egyeni_utazas'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Egyéni utazás', TD); ?></strong></label></p>
        <?php $kiemelt = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="checkbox" name="<?=$metakey?>" <?=($kiemelt=='1')?'checked="checked"':''?>>
    </td>
    <td>
      <?php $metakey = METAKEY_PREFIX . 'kiemelt'; ?>
      <p><label class="post-attributes-label" for="<?=$metakey?>"><strong><?php echo __('Kiemelt ajánlat', TD); ?></strong></label></p>
        <?php $kiemelt = get_post_meta($post->ID, $metakey, true); ?>
      <input id="<?=$metakey?>" type="checkbox" name="<?=$metakey?>" <?=($kiemelt=='1')?'checked="checked"':''?>>
    </td>
  </tr>
</table>
