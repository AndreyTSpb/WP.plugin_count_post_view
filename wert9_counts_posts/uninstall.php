<?php

if ( !defined('WP_UNINSTALL_PLUGIN') )  exit;

include dirname(__FILE__).'/check_table.php';

$table_name = "wert9_post_views";
if(!Check_Table::check($table_name)) return;

global $wpdb;
$table_name = $wpdb->get_blog_prefix() . $table_name;
$query = "DROP TABLE IF EXISTS {$table_name};";
$wpdb->query($query);
delete_option("wert9_counts_posts");