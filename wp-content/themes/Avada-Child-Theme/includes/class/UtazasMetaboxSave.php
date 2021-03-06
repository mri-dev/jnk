<?php
  class UtazasMetaboxSave implements MetaboxSaver
  {
    public function __construct()
    {
    }
    public function saving($post_id, $post)
    {
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'ar', $_POST[METAKEY_PREFIX . 'ar'] );
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'ar_akcios', $_POST[METAKEY_PREFIX . 'ar_akcios'] );
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'travel_from', $_POST[METAKEY_PREFIX . 'travel_from'] );
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'travel_to', $_POST[METAKEY_PREFIX . 'travel_to'] );
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'ar_magyarazat', $_POST[METAKEY_PREFIX . 'ar_magyarazat'] );
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'max_befogadas', $_POST[METAKEY_PREFIX . 'max_befogadas'] );
      
      $kiemelt = ($_POST[METAKEY_PREFIX . 'kiemelt'] == 'on') ? 1 : false;
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'kiemelt', $kiemelt );

      $egyeni = ($_POST[METAKEY_PREFIX . 'egyeni_utazas'] == 'on') ? 1 : false;
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'egyeni_utazas', $egyeni );

      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'photo_gallery_id', $_POST[METAKEY_PREFIX . 'photo_gallery_id'] );

      // Programok
      $programids = implode(",", $_POST['programok']);
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'programok', $programids );

      // Ajánlott utazások
      $utazasids = implode(",", $_POST['ajanlatok']);
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'ajanlatok', $utazasids );
    }
  }
?>
