<?php

$config = require_once get_theme_file_path('config/base.php');

add_action( 'wp_enqueue_scripts', function () use ($config) {

    $fa = [
        'handle'    => 'pandoramarketing/fontawesome/base',
        'src'       => WP_DEBUG
            ? $config['resources']['fontawesome']['local']['base']
            : $config['resources']['fontawesome']['cdn']['base'],
        'deps'      => [ ],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ];

    /**
     * --------------------------------------------------------------------------
     * Register Scripts
     * --------------------------------------------------------------------------
     *
     */
    register_assets('script', [
        'handle'    => 'pandoramarketing/package/jquery',
        'src'       => WP_DEBUG
            ? $config['resources']['package_jquery']['local']
            : $config['resources']['package_jquery']['cdn'],
        'deps'      => [ ],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandoramarketing/package/foundation',
        'src'       => $config['resources']['package_foundation'],
        'deps'      => [ ],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandoramarketing/js/main',
        'src'       => $config['resources']['script_main'],
        'deps'      => [ ],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register Styles
     * --------------------------------------------------------------------------
     *
     */
    register_assets('style', [
        'handle' => 'pandoramarketing/google/font',
        'src'    => $config['resources']['google_fonts'],
        'deps'   => [ ],
        'ver'    => file_version('0.0.1'),
        'media'  => 'all'
    ]);

    register_assets('style', [
        'handle' => 'pandoramarketing/style/main',
        'src'    => $config['resources']['style_main'],
        'deps'   => [ ],
        'ver'    => file_version('0.0.1'),
        'media'  => 'all'
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register Fontawesome
     * --------------------------------------------------------------------------
     *
     */
    wp_register_script($fa['handle'], $fa['src'], $fa['deps'], $fa['ver'], $fa['in_footer']);

    register_assets('script', [
        'handle'    => 'pandoramarketing/fontawesome/style',
        'src'       => WP_DEBUG
            ? $config['resources']['fontawesome']['local']['style']
            : $config['resources']['fontawesome']['cdn']['style'],
        'deps'      => [ $fa['handle'] ],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'pandoramarketing/fontawesome/brands',
        'src'       => WP_DEBUG
            ? $config['resources']['fontawesome']['local']['brands']
            : $config['resources']['fontawesome']['cdn']['brands'],
        'deps'      => [ $fa['handle'] ],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register google maps script
     * --------------------------------------------------------------------------
     *
     */
    register_assets('script', [
        'handle'    => 'pandora/js/map',
        'src'       => $config['resources']['script_map'],
        'deps'      => [],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    register_assets('script', [
        'handle'    => 'api-google-maps',
        'src'       => $config['resources']['google_maps'],
        'deps'      => [],
        'ver'       => file_version('0.0.1'),
        'in_footer' => true
    ]);

    /**
     * --------------------------------------------------------------------------
     * Register Scripts with conditionals
     * --------------------------------------------------------------------------
     *
     */
    if ( is_front_page() ) {
        register_assets('script', [
            'handle'    => 'pandoramarketing/package/fancyapps',
            'src'       => $config['resources']['package_fancyapps'],
            'deps'      => [ ],
            'ver'       => file_version('0.0.1'),
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandoramarketing/package/swiper',
            'src'       => $config['resources']['package_swiper'],
            'deps'      => [ ],
            'ver'       => file_version('0.0.1'),
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandoramarketing/package/dom7',
            'src'       => $config['resources']['package_dom7'],
            'deps'      => [ ],
            'ver'       => file_version('0.0.1'),
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandoramarketing/js/galleries',
            'src'       => $config['resources']['script_galleries'],
            'deps'      => [ ],
            'ver'       => file_version('0.0.1'),
            'in_footer' => true
        ]);

        register_assets('script', [
            'handle'    => 'pandoramarketing/js/sliders-homepage',
            'src'       => $config['resources']['script_sliders_homepage'],
            'deps'      => [ ],
            'ver'       => file_version('0.0.1'),
            'in_footer' => true
        ]);
    }
});
