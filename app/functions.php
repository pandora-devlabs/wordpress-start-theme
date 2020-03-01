<?php

/**
 * Functions
 * */
array_map(function ($file) {
    require_once get_theme_file_path("functions/") . "{$file}.php";
}, ['helpers', 'setup', 'enqueues', 'filters', 'acf', 'login']);

/**
 * Post Types
 * */
array_map(function ($file) {
    require_once get_theme_file_path("registers/post-types/") . "{$file}.php";
}, []);

/**
 * Taxonomies
 * */
array_map(function ($file) {
    require_once get_theme_file_path("registers/taxonomies/") . "{$file}.php";
}, []);
