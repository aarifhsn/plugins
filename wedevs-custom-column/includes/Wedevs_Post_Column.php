<?php

if (!class_exists('Wedevs_Post_Column')) {
    class Wedevs_Post_Column
    {
        public function __construct()
        {
            add_action('init', array($this, 'initialize'));
        }

        public function initialize()
        {
            add_filter('manage_page_posts_columns', array($this, 'wcc_post_column'), 10, 1);
            add_action('manage_page_posts_custom_column', array($this, 'wcc_post_column_content'), 10, 2);
            add_action('wp_head', array($this, 'count_post_view'));
            add_filter('manage_edit-page_sortable_columns', array($this, 'wcc_sortable_column'));
            add_action('pre_get_posts', array($this, 'wcc_column_orderby'));
        }


        /**
         * WCC Post Column
         * @param mixed $columns
         * @return array
         */
        public function wcc_post_column($columns)
        {
            $new_columns = array();

            foreach ($columns as $key => $column) {
                $new_columns[$key] = $column; // Add the current column to the new columns array

                // Add 'thumbnail' column after 'cb' (checkbox) column
                if ('cb' == $key) {
                    $new_columns['thumbnail'] = 'Thumbnail';
                }

                // add view column
                if ('author' == $key) {
                    $new_columns['view'] = 'View';
                }
            }
            return $new_columns; // Return the new columns array after the loop
        }

        public function wcc_default_hidden_columns($hidden, $screen)
        {
            if ($screen->post_type === 'page') {
                $hidden[] = 'view';
            }
            return $hidden;
        }


        /**
         * WCC Post Column Content
         * @param mixed $column_name
         * @param mixed $post_id
         * @return void
         */
        public function wcc_post_column_content($column_name, $post_id)
        {

            if ($column_name == 'thumbnail') {
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, 'thumbnail');
                } else {
                    echo '<img src="' . plugins_url('images/WordPress_blue_logo.svg.png', __FILE__) . '" width="80" height="80" alt="Default Image" />';
                }
            }
            if ($column_name == 'view') {
                $view_count = get_post_meta($post_id, '_wcc_view_count', true);
                echo $view_count;
            }
        }

        /**
         * Count Post View
         * @return void
         */
        public function count_post_view()
        {
            if (is_page()) {
                global $post;
                // GET PREVIOUS COUNT
                $view_count = get_post_meta($post->ID, '_wcc_view_count', true);
                if (!$view_count) {
                    $view_count = 0;
                } else {
                    $view_count = intval($view_count);
                }
                $view_count++;
                // SAVE NEW COUNT
                update_post_meta($post->ID, '_wcc_view_count', $view_count);
            }
        }

        /**
         * Summary of wcc_sortable_column
         * @param mixed $columns
         * @return string[]
         */
        public function wcc_sortable_column($columns)
        {
            $new_columns['view'] = 'view';
            return $new_columns;
        }


        /**
         * Sort page view options
         * @param mixed $query
         * @return void
         */
        public function wcc_column_orderby($query)
        {
            if (!is_admin()) {
                return;
            }

            $order_by = $query->get('orderby');
            if ($order_by == 'view') {
                $query->set('meta_key', '_wcc_view_count');
                $query->set('orderby', 'meta_value_num');
            }
        }
    }
}
