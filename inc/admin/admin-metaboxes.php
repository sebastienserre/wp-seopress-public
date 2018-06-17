<?php

defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Restrict SEO metaboxes to user roles
///////////////////////////////////////////////////////////////////////////////////////////////////
function seopress_advanced_security_metaboxe_role_hook_option() {
    $seopress_advanced_security_metaboxe_role_hook_option = get_option("seopress_advanced_option_name");
    if ( ! empty ( $seopress_advanced_security_metaboxe_role_hook_option ) ) {
        foreach ($seopress_advanced_security_metaboxe_role_hook_option as $key => $seopress_advanced_security_metaboxe_role_hook_value)
            $options[$key] = $seopress_advanced_security_metaboxe_role_hook_value;
         if (isset($seopress_advanced_security_metaboxe_role_hook_option['seopress_advanced_security_metaboxe_role'])) { 
            return $seopress_advanced_security_metaboxe_role_hook_option['seopress_advanced_security_metaboxe_role'];
         }
    }
}

function seopress_advanced_security_metaboxe_ca_role_hook_option() {
    $seopress_advanced_security_metaboxe_ca_role_hook_option = get_option("seopress_advanced_option_name");
    if ( ! empty ( $seopress_advanced_security_metaboxe_ca_role_hook_option ) ) {
        foreach ($seopress_advanced_security_metaboxe_ca_role_hook_option as $key => $seopress_advanced_security_metaboxe_ca_role_hook_value)
            $options[$key] = $seopress_advanced_security_metaboxe_ca_role_hook_value;
         if (isset($seopress_advanced_security_metaboxe_ca_role_hook_option['seopress_advanced_security_metaboxe_ca_role'])) { 
            return $seopress_advanced_security_metaboxe_ca_role_hook_option['seopress_advanced_security_metaboxe_ca_role'];
         }
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display metabox in Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////
function seopress_display_seo_metaboxe() {
    add_action('add_meta_boxes','seopress_init_metabox');
    function seopress_init_metabox(){
        if (function_exists('seopress_advanced_appearance_metaboxe_position_option')) {
            $seopress_advanced_appearance_metaboxe_position_option = seopress_advanced_appearance_metaboxe_position_option();
        } else {
            $seopress_advanced_appearance_metaboxe_position_option = 'default';
        }
        
        if (function_exists('seopress_get_post_types')) {
            
            $seopress_get_post_types = seopress_get_post_types();

            $seopress_get_post_types = apply_filters('seopress_metaboxe_seo', $seopress_get_post_types);
            
            foreach ($seopress_get_post_types as $key => $value) {
                add_meta_box('seopress_cpt', __('SEO','wp-seopress'), 'seopress_cpt', $key, 'normal', $seopress_advanced_appearance_metaboxe_position_option);
            }
            add_meta_box('seopress_cpt', __('SEO','wp-seopress'), 'seopress_cpt', 'seopress_404', 'normal', $seopress_advanced_appearance_metaboxe_position_option);
        }
    }

    function seopress_cpt($post){
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script( 'seopress-cpt-tabs-js', plugins_url( 'assets/js/seopress-tabs2.js', dirname(dirname(__FILE__ ))), array( 'jquery-ui-tabs' ));

        wp_enqueue_script( 'seopress-cpt-counters-js', plugins_url( 'assets/js/seopress-counters.js', dirname(dirname( __FILE__ ))), array( 'jquery', 'jquery-ui-accordion' ), '1.1' );

        $seopress_real_preview = array(
            'seopress_nonce' => wp_create_nonce('seopress_real_preview_nonce'),
            'seopress_real_preview' => admin_url('admin-ajax.php'),
        );
        wp_localize_script( 'seopress-cpt-counters-js', 'seopressAjaxRealPreview', $seopress_real_preview );
    
        wp_enqueue_script( 'seopress-media-uploader-js', plugins_url('assets/js/seopress-media-uploader.js', dirname(dirname( __FILE__ ))), array('jquery'), '', false );
        wp_enqueue_media();

        global $typenow;
        $seopress_titles_title                  = get_post_meta($post->ID,'_seopress_titles_title',true);
        $seopress_titles_desc                   = get_post_meta($post->ID,'_seopress_titles_desc',true);
        $seopress_robots_index                  = get_post_meta($post->ID,'_seopress_robots_index',true);
        $seopress_robots_follow                 = get_post_meta($post->ID,'_seopress_robots_follow',true);
        $seopress_robots_odp                    = get_post_meta($post->ID,'_seopress_robots_odp',true);
        $seopress_robots_imageindex             = get_post_meta($post->ID,'_seopress_robots_imageindex',true);
        $seopress_robots_archive                = get_post_meta($post->ID,'_seopress_robots_archive',true);
        $seopress_robots_snippet                = get_post_meta($post->ID,'_seopress_robots_snippet',true);
        $seopress_robots_canonical              = get_post_meta($post->ID,'_seopress_robots_canonical',true);
        if (is_plugin_active('wp-seopress-pro/seopress-pro.php')) {
            $seopress_robots_breadcrumbs            = get_post_meta($post->ID,'_seopress_robots_breadcrumbs',true);
        }
        $seopress_social_fb_title               = get_post_meta($post->ID,'_seopress_social_fb_title',true);
        $seopress_social_fb_desc                = get_post_meta($post->ID,'_seopress_social_fb_desc',true);
        $seopress_social_fb_img                 = get_post_meta($post->ID,'_seopress_social_fb_img',true);    
        $seopress_social_twitter_title          = get_post_meta($post->ID,'_seopress_social_twitter_title',true);
        $seopress_social_twitter_desc           = get_post_meta($post->ID,'_seopress_social_twitter_desc',true);
        $seopress_social_twitter_img            = get_post_meta($post->ID,'_seopress_social_twitter_img',true);
        $seopress_redirections_enabled          = get_post_meta($post->ID,'_seopress_redirections_enabled',true);
        $seopress_redirections_type             = get_post_meta($post->ID,'_seopress_redirections_type',true);
        $seopress_redirections_value            = get_post_meta($post->ID,'_seopress_redirections_value',true);
        if (is_plugin_active('wp-seopress-pro/seopress-pro.php')) {
            $seopress_news_disabled                 = get_post_meta($post->ID,'_seopress_news_disabled',true);
            $seopress_news_standout                 = get_post_meta($post->ID,'_seopress_news_standout',true);
            $seopress_news_genres                   = get_post_meta($post->ID,'_seopress_news_genres',true);
            $seopress_news_keyboard                 = get_post_meta($post->ID,'_seopress_news_keyboard',true);
            $seopress_video_disabled                = get_post_meta($post->ID,'_seopress_video_disabled',true);
            $seopress_video                         = get_post_meta($post->ID,'_seopress_video');
        }

        require_once ( dirname( __FILE__ ) . '/admin-metaboxes-form.php'); //Metaboxe HTML  
    }

    add_action('save_post','seopress_save_metabox');
    function seopress_save_metabox($post_id){
        if ( 'attachment' !== get_post_type($post_id)) {
            if(isset($_POST['seopress_titles_title'])){
              update_post_meta($post_id, '_seopress_titles_title', esc_html($_POST['seopress_titles_title']));
            }
            if(isset($_POST['seopress_titles_desc'])){
              update_post_meta($post_id, '_seopress_titles_desc', esc_html($_POST['seopress_titles_desc']));
            }
            if( isset( $_POST[ 'seopress_robots_index' ] ) ) {
                update_post_meta( $post_id, '_seopress_robots_index', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_robots_index', '' );
            }
            if( isset( $_POST[ 'seopress_robots_follow' ] ) ) {
                update_post_meta( $post_id, '_seopress_robots_follow', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_robots_follow', '' );
            }
            if( isset( $_POST[ 'seopress_robots_odp' ] ) ) {
                update_post_meta( $post_id, '_seopress_robots_odp', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_robots_odp', '' );
            }
            if( isset( $_POST[ 'seopress_robots_imageindex' ] ) ) {
                update_post_meta( $post_id, '_seopress_robots_imageindex', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_robots_imageindex', '' );
            }
            if( isset( $_POST[ 'seopress_robots_archive' ] ) ) {
                update_post_meta( $post_id, '_seopress_robots_archive', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_robots_archive', '' );
            }
            if( isset( $_POST[ 'seopress_robots_snippet' ] ) ) {
                update_post_meta( $post_id, '_seopress_robots_snippet', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_robots_snippet', '' );
            }
            if(isset($_POST['seopress_robots_canonical'])){
                update_post_meta($post_id, '_seopress_robots_canonical', esc_html($_POST['seopress_robots_canonical']));
            }
            if (is_plugin_active('wp-seopress-pro/seopress-pro.php')) {
                if(isset($_POST['seopress_robots_breadcrumbs'])){
                    update_post_meta($post_id, '_seopress_robots_breadcrumbs', esc_html($_POST['seopress_robots_breadcrumbs']));
                }
            }
            if(isset($_POST['seopress_social_fb_title'])){
                update_post_meta($post_id, '_seopress_social_fb_title', esc_html($_POST['seopress_social_fb_title']));
            }
            if(isset($_POST['seopress_social_fb_desc'])){
                update_post_meta($post_id, '_seopress_social_fb_desc', esc_html($_POST['seopress_social_fb_desc']));
            }
            if(isset($_POST['seopress_social_fb_img'])){
                update_post_meta($post_id, '_seopress_social_fb_img', esc_html($_POST['seopress_social_fb_img']));
            }
            if(isset($_POST['seopress_social_twitter_title'])){
                update_post_meta($post_id, '_seopress_social_twitter_title', esc_html($_POST['seopress_social_twitter_title']));
            }
            if(isset($_POST['seopress_social_twitter_desc'])){
                update_post_meta($post_id, '_seopress_social_twitter_desc', esc_html($_POST['seopress_social_twitter_desc']));
            }
            if(isset($_POST['seopress_social_twitter_img'])){
                update_post_meta($post_id, '_seopress_social_twitter_img', esc_html($_POST['seopress_social_twitter_img']));
            }         
            if(isset($_POST['seopress_redirections_type'])){
                update_post_meta($post_id, '_seopress_redirections_type', $_POST['seopress_redirections_type']);
            }     
            if(isset($_POST['seopress_redirections_value'])){
                update_post_meta($post_id, '_seopress_redirections_value', esc_html($_POST['seopress_redirections_value']));
            }
            if( isset( $_POST[ 'seopress_redirections_enabled' ] ) ) {
                update_post_meta( $post_id, '_seopress_redirections_enabled', 'yes' );
            } else {
                delete_post_meta( $post_id, '_seopress_redirections_enabled', '' );
            }
            if (is_plugin_active('wp-seopress-pro/seopress-pro.php')) {
                if( isset( $_POST[ 'seopress_news_disabled' ] ) ) {
                    update_post_meta( $post_id, '_seopress_news_disabled', 'yes' );
                } else {
                    delete_post_meta( $post_id, '_seopress_news_disabled', '' );
                }
                if( isset( $_POST[ 'seopress_news_standout' ] ) ) {
                    update_post_meta( $post_id, '_seopress_news_standout', 'yes' );
                } else {
                    delete_post_meta( $post_id, '_seopress_news_standout', '' );
                }
                if(isset($_POST['seopress_news_genres'])){
                    update_post_meta($post_id, '_seopress_news_genres', $_POST['seopress_news_genres']);
                }     
                if(isset($_POST['seopress_news_keyboard'])){
                    update_post_meta($post_id, '_seopress_news_keyboard', esc_html($_POST['seopress_news_keyboard']));
                }
                if( isset( $_POST[ 'seopress_video_disabled' ] ) ) {
                    update_post_meta( $post_id, '_seopress_video_disabled', 'yes' );
                } else {
                    delete_post_meta( $post_id, '_seopress_video_disabled', '' );
                }
                if(isset($_POST['seopress_video'])){
                    update_post_meta($post_id, '_seopress_video', $_POST['seopress_video']);
                }
            }
        }
    }
}

function seopress_display_ca_metaboxe() {
    add_action('add_meta_boxes','seopress_init_ca_metabox');
    function seopress_init_ca_metabox(){
        if (function_exists('seopress_advanced_appearance_metaboxe_position_option')) {
            $seopress_advanced_appearance_metaboxe_position_option = seopress_advanced_appearance_metaboxe_position_option();
        } else {
            $seopress_advanced_appearance_metaboxe_position_option = 'default';
        }
        if (function_exists('seopress_get_post_types')) {
            
            $seopress_get_post_types = seopress_get_post_types();

            $seopress_get_post_types = apply_filters('seopress_metaboxe_content_analysis', $seopress_get_post_types);
            
            foreach ($seopress_get_post_types as $key => $value) {
                add_meta_box('seopress_content_analysis', __('Content analysis','wp-seopress'), 'seopress_content_analysis', $key, 'normal',  $seopress_advanced_appearance_metaboxe_position_option);
            }
        }
    }

    function seopress_content_analysis($post) {
        //Loads JS/CSS
        wp_enqueue_script( 'seopress-content-analysis-ajax', plugins_url( 'assets/js/seopress-content-analysis.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery', 'jquery-ui-tabs' ), '', true );

        $seopress_content_analysis = array(
            'seopress_nonce' => wp_create_nonce('seopress_content_analysis_nonce'),
            'seopress_content_analysis' => admin_url( 'admin-ajax.php'),
        );
        wp_localize_script( 'seopress-content-analysis-ajax', 'seopressContentAnalysis', $seopress_content_analysis );
    
        $seopress_analysis_target_kw            = get_post_meta($post->ID,'_seopress_analysis_target_kw',true);
        $seopress_analysis_data                 = get_post_meta($post->ID,'_seopress_analysis_data');
        $seopress_titles_title                  = get_post_meta($post->ID,'_seopress_titles_title',true);
        $seopress_titles_desc                   = get_post_meta($post->ID,'_seopress_titles_desc',true);
        $seopress_robots_index                  = get_post_meta($post->ID,'_seopress_robots_index',true);
        $seopress_robots_follow                 = get_post_meta($post->ID,'_seopress_robots_follow',true);
        $seopress_robots_imageindex             = get_post_meta($post->ID,'_seopress_robots_imageindex',true);
        $seopress_robots_archive                = get_post_meta($post->ID,'_seopress_robots_archive',true);
        $seopress_robots_snippet                = get_post_meta($post->ID,'_seopress_robots_snippet',true);
        
        require_once ( dirname( __FILE__ ) . '/admin-metaboxes-content-analysis-form.php'); //Metaboxe HTML
    }

    add_action('save_post','seopress_save_ca_metabox');
    function seopress_save_ca_metabox($post_id){
        if ( 'attachment' !== get_post_type($post_id)) {
            if(isset($_POST['seopress_analysis_target_kw'])){
                update_post_meta($post_id, '_seopress_analysis_target_kw', esc_html($_POST['seopress_analysis_target_kw']));
            }
        }
    }
}

if (is_user_logged_in()) {
    global $wp_roles;
        
    //Get current user role
    if(isset(wp_get_current_user()->roles[0])) {
        $seopress_user_role = wp_get_current_user()->roles[0];

        //If current user role matchs values from Security settings then apply -- SEO Metaboxe
        if (function_exists('seopress_advanced_security_metaboxe_role_hook_option') && seopress_advanced_security_metaboxe_role_hook_option() !='') {
            if( array_key_exists( $seopress_user_role, seopress_advanced_security_metaboxe_role_hook_option())) {
                //do nothing
            } else {
                echo seopress_display_seo_metaboxe();
            }
        } else {
            echo seopress_display_seo_metaboxe();
        }

        //If current user role matchs values from Security settings then apply -- SEO Content Analysis
        if (function_exists('seopress_advanced_security_metaboxe_ca_role_hook_option') && seopress_advanced_security_metaboxe_ca_role_hook_option() !='') {
            if( array_key_exists( $seopress_user_role, seopress_advanced_security_metaboxe_ca_role_hook_option())) {
                //do nothing
            } else {
                echo seopress_display_ca_metaboxe();
            }
        } else {
            echo seopress_display_ca_metaboxe();
        }
    }   
}
