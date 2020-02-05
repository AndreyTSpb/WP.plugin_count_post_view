<?php
/*
Plugin Name: Коичество просмотра статей
Description: Подсчет количества просмотров статей
Plugin Url:
Author: Андрей
Version: 1.1
License: GPL2
 */

 /*
 0) Add fontawesome 
 1) Create new col in table wp_post
 2) Add filter on content
 3) Update field wert9_views
*/
include dirname(__FILE__).'/check_field.php';

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
    if(Check_Fiels::check('wert9_views')) return; //test field

    //obj connect to db
    global $wpdb;
    //$wpdb->posts - обращение к таблице пост для волдпресса, само подставит префикс
    $query = "ALTER TABLE $wpdb->posts ADD wert9_views INT NOT NULL DEFAULT '0'"; // create field

    $wpdb->query($query);
}

// count post views
function wert9_post_views($content){
    if( is_page() ) return $content; //если это страница, счетчик не показываем

    global $post;
    $views = $post->wert9_views;

    if(is_single()) $views += 1; //если мы внутри статьи то увеличиваем счетчик на 1
    
    $count_post_views = '<div><i class="fa fa-eye" aria-hidden="true"></i> '.$views.'</div>';
    return $content . $count_post_views;
}

//update field wert9_view
function wert9_add_views(){
    if(!is_single()) return; // если не отдельно взятая статья то ничего не делать
    global $post, $wpdb;

    $wert9_id = $post->ID; // id page
    $summ = $post->wert9_views + 1; // up one
    $wpdb->update(
        $wpdb->posts, // какую таблицу обновляем
        array('wert9_views' => $summ),// данные 
        array('ID' => $wert9_id)// условие
    );
}