<?php

// Ensure this file is not accessed directly
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Afs_Related_Posts
{
    /**
     * Constructor method.
     * Hooks the 'initialize' method to the 'init' action.
     */
    public function __construct()
    {
        add_action('init', array($this, 'initialize'));
    }

    /**
     * Initialization method.
     * Hooks additional methods to WordPress actions.
     */
    public function initialize()
    {
        // Hook to enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'afs_load_assets'));

        // Hook to modify the post content to include related posts
        add_filter('the_content', array($this, 'display_related_posts'));
    }

    /**
     * Enqueues the plugin's CSS and JavaScript assets.
     */
    public function afs_load_assets()
    {
        // Define the plugin version (ensure AFS_RP_VERSION is defined elsewhere)
        $version = defined('AFS_RP_VERSION') ? AFS_RP_VERSION : '1.0.0';

        // Construct the path to the assets directory
        $afs_assets_path = plugins_url('../assets', __FILE__); // Points to plugin's assets directory
        $afs_css_path = $afs_assets_path . '/css/';
        $afs_js_path = $afs_assets_path . '/js/';

        // Enqueue the plugin's CSS file
        wp_enqueue_style(
            'afs-rp-styles',
            $afs_css_path . 'afs-related-posts.css',
            array(),
            $version
        );

        // Enqueue the plugin's JavaScript file, with jQuery as a dependency
        wp_enqueue_script(
            'afs-rp-scripts',
            $afs_js_path . 'afs-related-posts.js',
            array('jquery'),
            $version,
            true // Load in footer
        );
    }

    /**
     * Appends related posts to the post content.
     *
     * @param string $content The original post content.
     * @return string Modified post content with related posts appended.
     */
    public function display_related_posts($content)
    {
        // Only modify content on single post pages
        if (is_singular('post')) {

            // Get the current post ID
            $current_post_id = get_the_ID();

            // Get the author's ID and avatar
            $author_id = get_the_author_meta('ID');
            $author_thumb = get_avatar($author_id, 32);

            // Retrieve the categories associated with the current post
            $categories = get_the_category($current_post_id);

            // Initialize related posts HTML
            $related_posts_html = '';

            // Check if the post has categories
            if (!empty($categories)) {

                // Prepare query arguments to fetch related posts
                $args = array(
                    'post_type'           => 'post',
                    'posts_per_page'      => 3,
                    'post__not_in'        => array($current_post_id), // Exclude current post
                    'category__in'        => wp_list_pluck($categories, 'term_id'),
                    'orderby'             => 'rand',
                    'ignore_sticky_posts' => true
                );

                // Execute the query
                $query = new WP_Query($args);

                // Check if any related posts were found
                if ($query->have_posts()) {

                    // Begin related posts section
                    $related_posts_html .= '<div class="afs_related_posts">';
                    $related_posts_html .= '<h5>' . __('Related Posts', 'related-posts') . '</h5>';
                    $related_posts_html .= '<ul class="afs_related_posts_list">';

                    // Loop through each related post
                    while ($query->have_posts()) {
                        $query->the_post();

                        // Get the related post's author information
                        $related_author_id = get_the_author_meta('ID');
                        $related_author_thumb = get_avatar($related_author_id, 32);
                        $related_author_name = get_the_author_meta('display_name', $related_author_id);
                        $related_author_bio = get_the_author_meta('description', $related_author_id);

                        // Build the HTML for a single related post
                        $related_posts_html .= '<li class="afs_related_post_item">';

                        // Post thumbnail
                        if (has_post_thumbnail()) {
                            $related_posts_html .= '<div class="post_thumb"><a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</a></div>';
                        }

                        // Post content
                        $related_posts_html .= '<div class="post_content">';

                        // Post date
                        $related_posts_html .= '<div class="post_date"><h6>' . get_the_date() . '</h6></div>';

                        // Post title
                        $related_posts_html .= '<div class="post_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></div>';

                        // Post excerpt
                        $related_posts_html .= '<div class="post_excerpt"><p>' . wp_trim_words(get_the_content(), 5, '...') . '</p></div>';

                        // Post author avatar
                        $related_posts_html .= '<div class="author_content">';
                        $related_posts_html .= '<div class="post_author">' . $related_author_thumb . '</div>';

                        // Post author name
                        $related_posts_html .= '<div class="author_info">';
                        $related_posts_html .= '<div class="post_author_name">' . __('by', 'related-posts') . ' ' . esc_html($related_author_name) . '</div>';

                        // Post author bio
                        if (!empty($related_author_bio)) {
                            $related_posts_html .= '<div class="post_author_bio">' . esc_html($related_author_bio) . '</div>';
                        }
                        $related_posts_html .= '</div>'; // .author_info
                        $related_posts_html .= '</div>'; // .author_content

                        // Close post content
                        $related_posts_html .= '</div>'; // .post_content

                        // Close list item
                        $related_posts_html .= '</li>';
                    }

                    // Close the list and related posts section
                    $related_posts_html .= '</ul>';
                    $related_posts_html .= '</div>'; // .afs_related_posts

                    // Reset post data to avoid conflicts
                    wp_reset_postdata();
                }
            }

            // Append the related posts HTML to the original content
            $content .= $related_posts_html;
        }

        // Return the modified or original content
        return $content;
    }

    /**
     * Placeholder run method.
     * You can implement additional functionality here if needed.
     */
    public function run() {}
}
