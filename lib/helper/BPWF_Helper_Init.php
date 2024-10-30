<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * A class which initiate the plugin
 */

 class BPWF_Helper_Init 
 {
     public function __construct()
     {
        // Init controller
        new BPWF_Controller_Controller();

        // Add all important scripts
        add_action('bp_enqueue_scripts', array($this, 'add_scripts'));
        add_filter( 'plugin_row_meta', array($this, 'bpwf_plugin_row_meta'), 10, 2 );
     }

     static function init()
     {
        // Database Version

        global $wpdb;

        global $bwf_db_version;

        $bwf_db_version = '1.0';

	    $table_name = $wpdb->prefix . 'bpwhofav';
	
	    $charset_collate = $wpdb->get_charset_collate();

	    $sql = "CREATE TABLE $table_name 
            (
		        id int NOT NULL AUTO_INCREMENT,
                activity_id int NOT NULL,
                fav_user_id int NOT NULL,
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        PRIMARY KEY  (id)
	        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
	    dbDelta( $sql );

        add_option( 'bwf_db_version', $bwf_db_version );
            
    }

    public function add_scripts()
    {
        // Load css
        wp_enqueue_style('bpwf',  plugins_url().'/bp-who-favorited/lib/view/css/bpwf.css');
        
    }

    public function bpwf_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'bp-who-favorited.php' ) !== false ) {
		$new_links = array(
				'donate' => '<a href="https://paypal.me/mastershas" target="_blank">Donate</a>'
				);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
    }
 
}