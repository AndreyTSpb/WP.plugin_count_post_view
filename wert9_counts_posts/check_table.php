<?php
class Check_Table 
{
    static function check($table_name){
        global $wpdb;

        $tables = $wpdb->get_results("SHOW TABLES;");
        foreach($tables as $table){
            if ($table->Tables_in_wordpress == $table_name) return true;
        }
        return false;
    }
}
