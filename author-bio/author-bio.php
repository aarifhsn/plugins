<?php

/**
 * Plugin Name: Author Bio
 * Plugin URI: http://wordpress.org/plugins/author-bio/
 * Description: Display the author bio
 * Version: 1.0
 * Author: Arif Hassan
 * Author URI: http://arif.me
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: afdevs
 **/

if (!defined('ABSPATH')) exit;

if (is_admin()) {
    // inclue includes/admin/profile.php file
    require_once dirname(__FILE__) . '/includes/admin/profile.php';
}

function afdevs_author_bio($content)
{
    // global $post - return the $post objects;
    global $post;

    $author = get_user_by('id', $post->post_author);

    $bio = get_user_meta($author->ID, 'Description', true); // return single value, if false, return array.
    $facebook = get_user_meta($author->ID, 'facebook', true);
    $twitter = get_user_meta($author->ID, 'twitter', true);
    $linkedIn = get_user_meta($author->ID, 'linkedIn', true);


    // if we use filter thats must be returned. We are using html codes below, so to return html, we can use ob_start();
    // this function will keep all the content below in its memory without returning. then when we clean this buffer , then it will return all together. 

    ob_start();
?>
    <div class="bio_wrap">
        <div class="avatar">
            <?php echo get_avatar($author->ID, 64); ?>
        </div>

        <div class="bio_content">
            <div class="author_name">
                <?php echo ucfirst($author->display_name); ?>
            </div>
            <div class="author_bio">
                <?php echo wpautop(wp_kses_post($bio)); ?>
            </div>

            <ul class="socials">
                <?php if ($twitter) { ?>
                    <li><a href="<?php echo esc_url($twitter); ?>"><?php _e('Twitter', 'afdevs'); ?></a></li> <?php } ?>
                <?php if ($facebook) { ?>
                    <li><a href="<?php echo esc_url($facebook); ?>"><?php _e('facebook', 'afdevs'); ?></a></li> <?php } ?>
                <?php if ($linkedIn) { ?>
                    <li><a href="<?php echo esc_url($linkedIn); ?>"><?php _e('linkedIn', 'afdevs'); ?></a></li> <?php } ?>
            </ul>
        </div>
    </div>
<?php
    $bio_content = ob_get_clean();

    return $content . $bio_content;
}

add_filter('the_content', 'afdevs_author_bio');


function afdevs_enqueue_style()
{
    wp_enqueue_style('author-bio', plugins_url('assets/css/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'afdevs_enqueue_style');
