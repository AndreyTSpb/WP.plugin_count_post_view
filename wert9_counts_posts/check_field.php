<?php

class Check_Fiels{

    static function check($column){
        global $wpdb;
    
        $fields = $wpdb->get_results("SHOW fields FROM $wpdb->posts", ARRAY_A);
        foreach($fields as $field){
            if($field['Field'] == $column) {
                return true;
            }
        }
        return false;
    }
}