<?php

get_header();

$queried_object = get_queried_object();

/**
 *
 * Validacion para custom post type o custom taxonomies
 *
 * Custom Post Type
 * @var $queried_object->query_var
 * if ( $queried_object->query_var == 'custom' ) {
 *     get_template_part('template-parts/pages/revista');
 * }
 *
 * Custom Taxonomy
 * @var $queried_object->taxonomy
 * if ( $queried_object->taxonomy == 'tax-revistas' ) {
 *     get_template_part('template-parts/pages/revista');
 * }
 *
 */

get_footer();
