<?php

if (!class_exists('Wedevs_Posts_Type')) {
    class Wedevs_Posts_Type
    {

        public function __construct()
        {
            add_action('init', array($this, 'initialize'));
        }

        public function initialize()
        {
            $labels = array(
                'name'               => _x('Wedevs Posts', 'wcc'),
                'singular_name'      => _x('Wedevs Post', 'wcc'),
                'menu_name'          => _x('Wedevs Posts', 'admin menu'),
                'name_admin_bar'     => _x('Wedevs Post', 'add new on admin bar'),
                'add_new'            => _x('Add New', 'Wedevs Post'),
                'add_new_item'       => __('Add New Wedevs Post'),
                'new_item'           => __('New Wedevs Post'),
                'edit_item'          => __('Edit Wedevs Post'),
                'view_item'          => __('View Wedevs Post'),
                'all_items'          => __('All Wedevs Posts'),
                'search_items'       => __('Search Wedevs Posts'),
                'parent_item_colon'  => __('Parent Wedevs Posts:'),
                'not_found'          => __('No Wedevs Posts found.'),
                'not_found_in_trash' => __('No Wedevs Posts found in Trash.'),
            );
            $args = array(
                'labels'             => $labels,
                'description'        => 'Post Type Description',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array('slug' => 'wedevs-post'),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 5,
                //'show_in_rest'       => false,
                'supports'           => array('title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt', 'trackbacks', 'comments', 'revisions', 'page-attributes', 'post-formats'),
            );
            register_post_type('wedevs-post', $args);
        }
    }
}
