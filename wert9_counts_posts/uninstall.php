<?php

if ( !defined('WP_UNINSTALL_PLUGIN') )  exit;

include dirname(__FILE__).'/check_field.php';
if(!Check_Fiels::check('wert9_views')) return; //test field

global $wpdb;
$query = "ALTER TABLE $wpdb->posts DROP wert9_views";
$wpdb->query($query);