<?php
/*
Plugin Name: Коичество просмотра статей
Description: Подсчет количества просмотров статей
Plugin Url:
Author: Андрей
Version: 2.1
License: GPL2
 */

 /*
 0) Add fontawesome 
 1) Create new col in table wp_post
 2) Add filter on content
 3) Update field wert9_views
*/
include dirname(__FILE__).'/check_table.php';

register_activation_hook(__FILE__, "wert9_create_field");
add_filter("the_content", "wert9_post_views");
add_action('wp_head','wert9_add_views'); //сработает при обращении к заголовку

//add font awesome
add_action( 'wp_enqueue_scripts', 'enqueue_font_awesome' );

function enqueue_font_awesome() {
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
}

//Create Field
function wert9_create_field(){
    $table_name = "wert9_post_views";
    if(Check_Table::check($table_name)) return;

    //obj connect to db
    global $wpdb;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';  // Для активации функции dbDelta

    $table_name = $wpdb->get_blog_prefix() . $table_name;
	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
    $query = "CREATE TABLE {$table_name} 
                ( `id` INT NOT NULL AUTO_INCREMENT , 
                  `views` INT NOT NULL DEFAULT '0' , 
                  PRIMARY KEY (`id`)
                ) 
                {$charset_collate};
            ";
    dbDelta($query);
}


// count post views
function wert9_post_views($content){
    if( is_page() ) return $content; //если это страница, счетчик не показываем

    global $post,$wpdb;
    $views = get_row_table($wpdb->get_blog_prefix() . "wert9_post_views");
    
    if(is_single()) $views += 1; //если мы внутри статьи то увеличиваем счетчик на 1
    
    $count_post_views = '<div><i class="fa fa-eye" aria-hidden="true"></i> '.$views.'</div>';
    return $content . $count_post_views;
}

//update field wert9_view
function wert9_add_views(){
    if(!is_single()) return; // если не отдельно взятая статья то ничего не делать
    global $post, $wpdb;

    $table_name = $wpdb->get_blog_prefix() . "wert9_post_views";

    $wert9_id = $post->ID; // id page
    $summ = get_row_table($table_name) + 1; // up one
    if($summ > 1){
        $wpdb->update(
            $table_name, // какую таблицу обновляем
            array('views' => $summ),// данные 
            array('id' => $wert9_id)// условие
        );
    }else{
        $wpdb->insert(
            $table_name, // какую таблицу обновляем
            array('id' => $wert9_id, 'views' => $summ),// данные 
            array('%d', '%d')// условие
        );
    }
    
}

function get_row_table($table_name){
    global $post, $wpdb;
    $query = "SELECT views FROM {$table_name} WHERE id = {$post->ID}";
    $res = $wpdb->get_row($query);
    if(!$res) return 0;
    return $res->views;
}
