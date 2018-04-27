<?php
  global $wp_query, $traveler;

  $postid = $wp_query->query_vars['postid'];
  $config_group = $wp_query->query_vars['travel_group'];
  $mode = $wp_query->query_vars['travel_mode'];
  $termid = $wp_query->query_vars['travel_termid'];

  $traveler = new TravelModul($postid);

  get_template_part('templates/admin/travelmodalconfig/'.$config_group);
?>
