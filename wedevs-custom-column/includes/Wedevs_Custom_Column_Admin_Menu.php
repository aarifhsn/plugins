<?php

class Wedevs_Custom_Column_Admin_Menu
{

    public function __construct()
    {
        add_action('init', array($this, 'wcc_init'));
    }

    public function wcc_init()
    {
        add_action('admin_menu', array($this, 'add_menu_page'));
    }

    public function add_menu_page()
    {

        add_menu_page(
            'Wedevs Custom Column',
            'Wedevs Custom Column',
            'manage_options',
            'wcc',
            array($this, 'wcc_customize_menu_page')
        );
    }

    public function wcc_customize_menu_page()
    {
        $posts_args = array(
            'post_type' => 'post',

        );
        if (isset($_GET['wcc_cat']) && $_GET['wcc_cat'] != '-1') {
            $posts_args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $_GET['wcc_cat']
                ),
            );
        }

        $posts = get_posts($posts_args);

        $terms = get_terms(array(
            'taxonomy' => 'category'
        ));

        include_once plugin_dir_path(__FILE__) . 'templates/wcc_customize_menu_page.php';
    }
}
