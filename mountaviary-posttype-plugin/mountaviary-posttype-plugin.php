<?php
/**
 * @package Mountaviary
 */
/*
    Plugin Name: Mountavaiary Custom Posts Plugin
    Plugin URI: https://mountaviary.com/
    Description: This plugin creates two custom post types: Portfolio and Services, each with its own custom templates. By utilizing the Front Page template provided in the theme, you can achieve distinct looks for your portfolio website using these custom post types. In this theme, you'll find a Front Page template that offers a unique design for your personal portfolio website.
    Additionally, the plugin offers shortcode options. You can use the shortcode [categoryposts] to display posts from specific categories on different pages. 
    If you encounter any errors or issues with this plugin, please let us know so we can assist you further.
    Version: 1.0.0
    Requires at least: 5.8
    Requires PHP: 5.6.20
    Author: Arif Hassan 
    Author URI: https://mountaviary.com/
    License: GPLv2 or later
    Text Domain: mountaviary
    */
 
 /**
  * Register custom post types called "Portfolio" and "mav_services".
  *
  * @see get_post_type_labels() for label keys.
  */
 
 function mountaviary_custom_posts_init() {
 
    register_post_type( 'mav_portfolio',
       array (
           'labels' => array(
             'name'                  => __( 'Portfolio', 'mountaviary' ),
             'singular_name'         => __( 'Portfolio', 'mountaviary' ),
             'add_new'               => __( 'Add New', 'mountaviary' ),
             'add_new_item'          => __( 'Add New Portfolio', 'mountaviary'),
             'new_item'              => __( 'New Portfolio', 'mountaviary' ),
             'edit_item'             => __( 'Edit Portfolio', 'mountaviary' ),
             'view_item'             => __( 'View Portfolio', 'mountaviary' ),
             'all_items'             => __( 'All Portfolio', 'mountaviary' ),
             ),
          'menu_icon'          => 'dashicons-portfolio',
          'public'             => true, 
          'rewrite'            => array( 'slug' => 'portfolio' ),
          'capability_type'    => 'post',
          'menu_position'      => 6,
          'supports'           => array( 'title', 'thumbnail','editor'),
       )
    );

    $labels = array(
      'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', 'text_domain' ),
      'singular_name'              => _x( 'Portfolio Category', 'Taxonomy Singular Name', 'text_domain' ),
      'menu_name'                  => __( 'Category', 'text_domain' ),
      'all_items'                  => __( 'All Categories', 'text_domain' ),
      'parent_item'                => __( 'Parent Category', 'text_domain' ),
      'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
      'new_item_name'              => __( 'New Category Name', 'text_domain' ),
      'add_new_item'               => __( 'Add New Category', 'text_domain' ),
      'edit_item'                  => __( 'Edit Category', 'text_domain' ),
      'update_item'                => __( 'Update Category', 'text_domain' ),
      'view_item'                  => __( 'View Category', 'text_domain' ),
  );

  $args = array(
      'labels'                     => $labels,
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'rewrite'                    => array( 'slug' => 'portfolio-category' ), // Adjust the slug as needed
  );
  register_taxonomy( 'portfolio_category', array( 'mav_portfolio' ), $args );
 
    register_post_type( 'mav_service',
       array (
           'labels' => array(
             'name'                  => __( 'Services', 'mountaviary' ),
             'singular_name'         => __( 'Service', 'mountaviary' ),
             'add_new'               => __( 'Add New', 'mountaviary' ),
             'add_new_item'          => __( 'Add New Service', 'mountaviary'),
             'new_item'              => __( 'New Service', 'mountaviary' ),
             'edit_item'             => __( 'Edit Service', 'mountaviary' ),
             'view_item'             => __( 'View Service', 'mountaviary' ),
             'all_items'             => __( 'All Service', 'mountaviary' ),
             ),
          'menu_icon'          => 'dashicons-index-card',
          'public'             => true, 
          'rewrite'            => array( 'slug' => 'service' ),
          'capability_type'    => 'post',
          'menu_position'      => 7,
          'supports'           => array( 'title', 'editor' ),
       )
    );
     
 }
 
 add_action( 'init', 'mountaviary_custom_posts_init' );

// posts by category function
// user can show specifiq category posts to a new page using this shortcode

function mount_postsbycategory($atts) {
   // Extract shortcode attributes
   $atts = shortcode_atts(array(
       'posts_per_page' => 5, // Default value for posts per page
       'category_name' => 'curated', // Default value for category name
   ), $atts);

   // Initialize the string variable
   $string = '';

   // Get the current page number
   $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

   // the query
   $the_query = new WP_Query( array( 
       'category_name' => $atts['category_name'], // Use the dynamic category name
       'posts_per_page' => $atts['posts_per_page'], // Use the dynamic value
       'paged' => $paged // Pagination
   ) ); 
       
   // The Loop
   if ( $the_query->have_posts() ) {
       while ( $the_query->have_posts() ) {
           $the_query->the_post();
           // Get the post ID
           $post_id = get_the_ID();
           
           // Start output buffering
           ob_start();
           // Start post_page_content div
           $string .= '<div class="post_page_content py-8 border-b-2 border-slate-200">';
               if ( has_post_thumbnail() ) {
               $string .= '<div class="thumbnail overflow-hidden">';
                   $string .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( null, 'medium', array("class" => "w-full h-auto hover:scale-110 duration-300 rounded-t-lg")) . '</a>';
               $string .= '</div>';
               }
               $string .= '<div class="my-4 text-xl font-semibold text-slate-700 break-words">';
           
                   $string .= '<h2 class="entry-title"><a class="hover:text-slate-950" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_title() . '</a></h2>';

                   $string .= '<h4 class="text-sm text-slate-500 mb-2 py-4 font-medium leading-6">' . get_the_excerpt() . '</h4>';
               
               $string .= '</div>';

           // End post_page_content div
           $string .= '</div>';
           // End output buffering, get contents, and append to the string
           $string .= ob_get_clean();
       }
       // Pagination
       $string .= '<div class="pagination">';
       $string .= paginate_links(array(
           'total' => $the_query->max_num_pages
       ));
       $string .= '</div>';
   } else {
       // no posts found
       ob_start();
       get_template_part("404");
       $string .= ob_get_clean();
   }
       
   // Restore original Post Data
   wp_reset_postdata();

   // Return the result
   return $string;
}
// Add a shortcode
add_shortcode('categoryposts', 'mount_postsbycategory');