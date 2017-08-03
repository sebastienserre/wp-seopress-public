<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

//Export SEOPress Settings in JSON
function seopress_export_settings() {
    if( empty( $_POST['seopress_action'] ) || 'export_settings' != $_POST['seopress_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['seopress_export_nonce'], 'seopress_export_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;
    
    $settings["seopress_activated"]                     = get_option( 'seopress_activated' );
    $settings["seopress_titles_option_name"]            = get_option( 'seopress_titles_option_name' );
    $settings["seopress_social_option_name"]            = get_option( 'seopress_social_option_name' );
    $settings["seopress_google_analytics_option_name"]  = get_option( 'seopress_google_analytics_option_name' );
    $settings["seopress_advanced_option_name"]          = get_option( 'seopress_advanced_option_name' );
    $settings["seopress_xml_sitemap_option_name"]       = get_option( 'seopress_xml_sitemap_option_name' );
    $settings["seopress_pro_option_name"]               = get_option( 'seopress_pro_option_name' );
    $settings["seopress_pro_license_key"]               = get_option( 'seopress_pro_license_key' );
    $settings["seopress_pro_license_status"]            = get_option( 'seopress_pro_license_status' );
    $settings["seopress_bot_option_name"]               = get_option( 'seopress_bot_option_name' );
    $settings["seopress_toggle"]                        = get_option( 'seopress_toggle' );

    ignore_user_abort( true );
    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=seopress-settings-export-' . date( 'm-d-Y' ) . '.json' );
    header( "Expires: 0" );
    echo json_encode( $settings );
    exit;
}
add_action( 'admin_init', 'seopress_export_settings' );

//Import SEOPress Settings from JSON
function seopress_import_settings() {
    if( empty( $_POST['seopress_action'] ) || 'import_settings' != $_POST['seopress_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['seopress_import_nonce'], 'seopress_import_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;
    $extension = end( explode( '.', $_FILES['import_file']['name'] ) );
    if( $extension != 'json' ) {
        wp_die( __( 'Please upload a valid .json file' ) );
    }
    $import_file = $_FILES['import_file']['tmp_name'];
    if( empty( $import_file ) ) {
        wp_die( __( 'Please upload a file to import' ) );
    }

    $settings = (array) json_decode( file_get_contents( $import_file ), true );

    update_option( 'seopress_activated', $settings["seopress_activated"] ); 
    update_option( 'seopress_titles_option_name', $settings["seopress_titles_option_name"] ); 
    update_option( 'seopress_social_option_name', $settings["seopress_social_option_name"] ); 
    update_option( 'seopress_analytics_option_name', $settings["seopress_analytics_option_name"] ); 
    update_option( 'seopress_advanced_option_name', $settings["seopress_advanced_option_name"] ); 
    update_option( 'seopress_xml_sitemap_option_name', $settings["seopress_xml_sitemap_option_name"] ); 
    update_option( 'seopress_pro_option_name', $settings["seopress_pro_option_name"] );
    update_option( 'seopress_pro_license_key', $settings["seopress_pro_license_key"] );
    update_option( 'seopress_pro_license_status', $settings["seopress_pro_license_status"] );
    update_option( 'seopress_bot_option_name', $settings["seopress_bot_option_name"] );
    update_option( 'seopress_toggle', $settings["seopress_toggle"] );
     
    wp_safe_redirect( admin_url( 'admin.php?page=seopress-import-export' ) ); exit;
}
add_action( 'admin_init', 'seopress_import_settings' );

//Reset SEOPress Notices Settings
function seopress_reset_notices_settings() {
    if( empty( $_POST['seopress_action'] ) || 'reset_notices_settings' != $_POST['seopress_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['seopress_reset_notices_nonce'], 'seopress_reset_notices_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    global $wpdb;
    
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'seopress_notices' ");
     
    wp_safe_redirect( admin_url( 'admin.php?page=seopress-import-export' ) ); exit;
}
add_action( 'admin_init', 'seopress_reset_notices_settings' );

//Reset SEOPress Settings
function seopress_reset_settings() {
    if( empty( $_POST['seopress_action'] ) || 'reset_settings' != $_POST['seopress_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['seopress_reset_nonce'], 'seopress_reset_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    global $wpdb;
    
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'seopress_%' ");
     
    wp_safe_redirect( admin_url( 'admin.php?page=seopress-import-export' ) ); exit;
}
add_action( 'admin_init', 'seopress_reset_settings' );

//Export SEOPress Bot Links in CSV
function seopress_bot_links_export_settings() {
    if( empty( $_POST['seopress_action'] ) || 'export_csv_links_settings' != $_POST['seopress_action'] )
        return;
        
    if( ! wp_verify_nonce( $_POST['seopress_export_csv_links_nonce'], 'seopress_export_csv_links_nonce' ) )
        return;
    
    if( ! current_user_can( 'manage_options' ) )
        return;

    $args = array(
        'post_type' => 'seopress_bot',
        'posts_per_page' => 1000,
        'post_status' => 'publish',
        'order' => 'DESC',
        'orderby' => 'date',
    );
    $the_query = new WP_Query( $args );
    
    $settings["URL"] = array();
    $settings["Source"] = array();
    $settings["Source_Url"] = array();
    $settings["Status"] = array();
    $settings["Type"] = array(); 
    
    $csv_fields = array();
    $csv_fields[] = 'URL';
    $csv_fields[] = 'Source';
    $csv_fields[] = 'Source URL';
    $csv_fields[] = 'Status';
    $csv_fields[] = 'Type';
    
    $output_handle = @fopen( 'php://output', 'w' );
    
    //Insert header row
    fputcsv( $output_handle, $csv_fields );
    
    //Header
    ignore_user_abort( true );
    nocache_headers();
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=seopress-links-export-' . date( 'm-d-Y' ) . '.csv' );
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    
    // The Loop
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            
            array_push($settings["URL"], get_the_title());
            
            if (get_post_meta( get_the_ID(), 'seopress_bot_source_title', true ) !='') {
                array_push($settings["Source"], get_post_meta( get_the_ID(), 'seopress_bot_source_title', true ));    
            }

            if (get_post_meta( get_the_ID(), 'seopress_bot_source_url', true ) !='') {
                array_push($settings["Source_Url"], get_post_meta( get_the_ID(), 'seopress_bot_source_url', true ));    
            }

            if (get_post_meta( get_the_ID(), 'seopress_bot_status', true ) !='') {
                array_push($settings["Status"], get_post_meta( get_the_ID(), 'seopress_bot_status', true ));    
            }

            if (get_post_meta( get_the_ID(), 'seopress_bot_type', true ) !='') {
                array_push($settings["Type"], get_post_meta( get_the_ID(), 'seopress_bot_type', true ));    
            }

            fputcsv( $output_handle, array_merge($settings["URL"], $settings["Source"], $settings["Source_Url"], $settings["Status"], $settings["Type"]));
            
            //Clean arrays
            $settings["URL"] = array();
            $settings["Source"] = array();
            $settings["Source_Url"] = array();
            $settings["Status"] = array();
            $settings["Type"] = array(); 

        }
        wp_reset_postdata();
    }    
    
    // Close output file stream
    fclose( $output_handle );
    
    exit;
}
add_action( 'admin_init', 'seopress_bot_links_export_settings' );