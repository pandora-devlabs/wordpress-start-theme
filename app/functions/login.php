<?php

if ( !function_exists('pm_custom_login_stylesheet') ):
    function pm_custom_login_stylesheet() {
        wp_enqueue_style(
            'custom-login',
            get_stylesheet_directory_uri() . '/static/css/wp_login_styles.css'
        );
    }
endif;
add_action( 'login_enqueue_scripts', 'pm_custom_login_stylesheet' );

if (!function_exists('pm_login_logo') ):
    function pm_login_logo() {
        $uri = get_stylesheet_directory_uri();

        $style = "<style type='text/css'>";
        $style .= "body.login { background-image: url($uri/static/images/login/background.png); }";
        $style .= "#login h1 a, .login h1 a {  background-image: url($uri/static/images/login/logo-white.svg); }";
        $style .= "</style>";

        echo $style;
    }
endif;
add_action( 'login_enqueue_scripts', 'pm_login_logo' );

if ( !function_exists('pm_login_logo_url') ):
    function pm_login_logo_url() {
        return home_url();
    }
endif;
add_filter( 'login_headerurl', 'pm_login_logo_url' );
