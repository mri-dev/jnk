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
      $kiemelt = ($_POST[METAKEY_PREFIX . 'kiemelt'] == 'on') ? 1 : false;
      auto_update_post_meta( $post_id, METAKEY_PREFIX . 'kiemelt', $kiemelt );
    }
  }
?>
