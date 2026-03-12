<?php


add_action('acf/init', 'acf_init_blocks');
function acf_init_blocks()
{

    // check function exists
    if (function_exists('acf_register_block_type')) {

        // register a faq
        acf_register_block_type(array(
            'name'                => 'acf_accordion_faq',
            'title'               => __('faq accordion'),
            'render_callback'      => 'my_acf_block_render_callback',
            'render_template'     => 'inc/widgets/acf_accordion_faq.php',
            'category'            => 'widgets',
            'icon'                => 'screenoptions',
            'keywords'            => array('faq'),
        ));

        // register a multi button
        acf_register_block_type(array(
            'name'                => 'acf_button',
            'title'               => __('multi button'),
            'render_callback'      => 'my_acf_block_render_callback',
            'render_template'     => 'inc/widgets/acf_button.php',
            'category'            => 'widgets',
            'icon'                => 'screenoptions',
            'keywords'            => array('button'),
        ));

        // register a gallery slider
        acf_register_block_type(array(
            'name'                => 'acf_gallery_slider',
            'title'               => __('gallery slider'),
            'render_callback'      => 'my_acf_block_render_callback',
            'render_template'     => 'inc/widgets/acf_gallery_slider.php',
            'category'            => 'widgets',
            'icon'                => 'screenoptions',
            'keywords'            => array('gallery'),
        ));

        // register a grid gllery
        acf_register_block_type(array(
            'name'                => 'acf_grid_gallery',
            'title'               => __('grid gallery'),
            'render_callback'      => 'my_acf_block_render_callback',
            'render_template'     => 'inc/widgets/acf_grid_gallery.php',
            'category'            => 'widgets',
            'icon'                => 'screenoptions',
            'keywords'            => array('gallery'),
        ));


    }

}