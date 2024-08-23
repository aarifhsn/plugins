<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Afs_Related_Posts_Display
{
    /**
     * Initialize the display logic.
     * Hooks the 'display_related_posts' method to the 'the_content' filter.
     */
    public function init()
    {
        add_filter('the_content', array($this, 'display_related_posts'));
    }

    /**
     * Appends related posts to the post content.
     */
    public function display_related_posts($content)
    {
        // If it is on single post pages
        if (is_singular('post')) {

            // Get the current post ID
            $current_post_id = get_the_ID();

            // Get the categories of the current post
            $categories = get_the_category($current_post_id);

            // Initialize the related posts HTML
            $related_posts_html = '';

            // Check if the post has categories
            if (!empty($categories)) {

                // Prepare query arguments to fetch related posts
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'post__not_in' => array($current_post_id), // Exclude the current post
                    'category__in' => wp_list_pluck($categories, 'term_id'),
                    'orderby' => 'rand',
                    'ignore_sticky_posts' => true,
                );

                // Execute the query to get related posts
                $query = new WP_Query($args);

                // Check if any related posts were found
                if ($query->have_posts()) {

                    // Begin building the related posts HTML
                    $related_posts_html .= '<div class="afs_related_posts">';
                    $related_posts_html .= '<h5>' . esc_html__('Related Posts', 'related-posts') . '</h5>';
                    $related_posts_html .= '<ul class="owl-carousel">';

                    // Loop through each related post
                    while ($query->have_posts()) {
                        $query->the_post();

                        // Build the HTML for a single related post
                        $related_posts_html .= '<li class="afs_related_post_item">';

                        // Add post thumbnail
                        if (has_post_thumbnail()) {
                            $related_posts_html .= '<div class="post_thumb"><a href="' . esc_url(get_permalink()) . '">' . get_the_post_thumbnail(get_the_ID(), 'large') . '</a></div>';
                        }

                        // Add post content
                        $related_posts_html .= '<div class="post_content">';

                        // Add post date
                        $related_posts_html .= '<div class="post_date"><h6>' . esc_html(get_the_date()) . '</h6></div>';

                        // Add post title
                        $related_posts_html .= '<div class="post_title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></div>';

                        // Add post excerpt
                        $related_posts_html .= '<div class="post_excerpt"><p>' . esc_html(wp_trim_words(get_the_content(), 5, '...')) . '</p></div>';

                        // Add post author information
                        $related_author_id = get_the_author_meta('ID');
                        $related_author_thumb = get_avatar($related_author_id, 32);
                        $related_author_name = get_the_author_meta('display_name', $related_author_id);
                        $related_author_bio = get_the_author_meta('description', $related_author_id);

                        $related_posts_html .= '<div class="author_content">';
                        $related_posts_html .= '<div class="post_author">' . $related_author_thumb . '</div>'; // Author avatar
                        $related_posts_html .= '<div class="author_info">';
                        $related_posts_html .= '<div class="post_author_name">' . esc_html__('by', 'related-posts') . ' ' . esc_html($related_author_name) . '</div>';

                        // Add author bio if available
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

            // Append the related posts HTML to the content
            $content .= $related_posts_html;
        }

        // Return the modified content
        return $content;
    }
}
