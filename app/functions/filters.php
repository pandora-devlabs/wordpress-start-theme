<?php

add_filter('script_loader_tag', function($tag, $handle) {
    if ( 'api-google-maps' !== $handle )
        return $tag;
    return str_replace( ' src', ' async defer src', $tag );
}, 10, 2);
