<?php

//if($post->ID == '591'){
//    wc_get_template_part( 'single-product-besshovnoe' ); 
//} else{
//    wc_get_template_part( 'single-product-default' );
//}

global $post;

$terms = wp_get_post_terms($post->ID, 'product_cat');

foreach ($terms as $term) $categories[] = $term->slug;
if (in_array('besshovnoe-pokrytie', $categories)) {

    if ($post->ID == 2025 || $post->ID == 2582) {
        wc_get_template_part('single', 'product-besshovnoe2025');
    } else {
        wc_get_template_part('single', 'product-besshovnoe');
    }
} else {
    wc_get_template_part('single', 'product-default');
}
