<?php


/**
 * --------------------------------------------------------------------------
 * Helper | register_assets();
 * --------------------------------------------------------------------------
 *
 * @param $type
 * @param $resource
 *
 */
function register_assets($type, $resource) {
    if ($type === 'style') {
        wp_register_style(
            $resource['handle'],
            $resource['src'],
            $resource['deps'],
            $resource['ver'],
            $resource['media']
        );
        wp_enqueue_style( $resource['handle'] );
    } elseif ($type === 'script') {
        wp_register_script(
            $resource['handle'],
            $resource['src'],
            $resource['deps'],
            $resource['ver'],
            $resource['in_footer']
        );
        wp_enqueue_script( $resource['handle'] );
    }
}


/**
 * --------------------------------------------------------------------------
 * Helper | dd();
 * --------------------------------------------------------------------------
 *
 * @param $variable
 *
 */
function dd($variable) {
    $styles = "
    #dd {
        background-color: black;
        color: #fff;
    }
    #dd small {
        color: #fff000;
    }
    ";

    echo "<div id='dd'><pre>";
    echo "<style>{$styles}</style>";
    var_dump($variable);
    echo "</pre></div>";
}


/**
 * --------------------------------------------------------------------------
 * Helper | File version
 * --------------------------------------------------------------------------
 *
 * @param string $version_file
 *
 * @return int|string
 *
 */
function file_version ( $version_file = '0.0.1' ) {
    return WP_DEBUG ? time() : $version_file;
}


/**
 * --------------------------------------------------------------------------
 * Helper | Autoload functions custom post type or taxonomy
 * --------------------------------------------------------------------------
 *
 * @param string $path
 *
 * @return array
 *
 */
function __autoload_functions_by_dir(String $path) {
    $dir = scandir(get_template_directory() . $path);
    $files = [];

    foreach ( $dir as $key => $file ) {
        if ( ! in_array($file, ['.', '..', '.gitkeep']) ) {
            $files[] = substr($file, 0, -4);
        }
    }

    return $files;
}


/**
 * --------------------------------------------------------------------------
 * Helper | Get Menu
 * --------------------------------------------------------------------------
 *
 * @param $menu_name
 *
 * @return array|bool
 *
 */
function get_menu_custom($menu_name) {
    if ( ($locations = get_nav_menu_locations()) && isset( $locations[$menu_name] ) ) {
        $array_menu = wp_get_nav_menu_items($locations[$menu_name]);

        return build_tree($array_menu);
    } else {
        return false;
    }
}

function build_tree(array &$elements, $parentId = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element->menu_item_parent == $parentId) {
            $children = build_tree($elements, $element->ID);
            if ($children) {
                $element->children = $children;
            }
            $branch[$element->ID] = $element;
            unset($elements[$element->ID]);
        }
    }
    return $branch;
}


/**
 * --------------------------------------------------------------------------
 * Helper | Pagination
 * --------------------------------------------------------------------------
 *
 */
function numeric_posts_nav() {

    if ( is_singular() ) {
        return;
    }

    global $wp_query;

    /** Stop execution if there's only 1 page */
    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }

    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );

    /** Add current page to the array */
    if ( $paged >= 1 ) {
        $links[] = $paged;
    }

    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    echo '<nav aria-label="Pagination" style="margin-top: 3rem;">';
    echo '<ul class="pagination text-center">' . "\n";

    /** Previous Post Link */
    if ( get_previous_posts_link() ):
        printf( '<li class="pagination-previous">%s</li>' . "\n", get_previous_posts_link( 'Anterior' ) );
    endif;

    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';

        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

        if ( ! in_array( 2, $links ) ) {
            echo '<li class="ellipsis"></li>';
        }
    }

    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="current"' : '';
        if ( $paged == $link ):
            printf( '<li%s>%s</li>' . "\n", $class, $link );
        else:
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
        endif;
    }

    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) ) {
            echo '<li class="ellipsis"></li>' . "\n";
        }

        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }

    /** Next Post Link */
    if ( get_next_posts_link() ) {
        printf( '<li class="pagination-next">%s</li>' . "\n", get_next_posts_link( 'Siguiente' ) );
    }

    echo '</ul></nav>' . "\n";
}


/**
 * --------------------------------------------------------------------------
 * Helper | Get Thumbnail
 * --------------------------------------------------------------------------
 *
 * @param $post
 *
 * @return string
 *
 */
function get_thumbnail_as_background_by_id ($post) {
    if ( isset($post->ID) ) {
        if ( get_the_post_thumbnail_url($post->ID) ) {
            return "background-image: url('" . get_the_post_thumbnail_url($post->ID) . "');";
        } else {
            return "background-image: url('" . get_theme_file_uri('static/images/thumbnail-default.jpg') . "');";
        }
    } else {
        return "background-image: url('" . get_theme_file_uri('static/images/thumbnail-default.jpg') . "');";
    }
}


/**
 * --------------------------------------------------------------------------
 * Helper | Password Form
 * --------------------------------------------------------------------------
 *
 */
function get_the_password_form_custom( $post = 0 ) {
    $post   = get_post( $post );
    $label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
    $output = '<div class="grid-container margin-top-3"><div class="grid-x align-center"><div class="cell medium-6"><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
    <p class="text-center font-bold">' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
    <p><label class="text-center" for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input class="button width-100" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '" /></p></form></div></div></div></section>';

    return apply_filters( 'the_password_form', $output );
}
