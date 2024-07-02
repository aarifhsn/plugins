<div class="wrap">
    <h1 class="wp-heading-inline">
        Customized Posts</h1>

    <a href="" class="page-title-action">Add New Post</a>
    <hr class="wp-header-end">

    <div class="tablenav top">
        <form method="get">
            <input type="hidden" name="page" value="wcc">
            <div class="alignleft actions">

                <?php $selected_category = isset($_GET['wcc_cat']) ? $_GET['wcc_cat'] : '-1'; ?>

                <label class="screen-reader-text" for="cat">Filter by category</label>
                <select name="wcc_cat" id="cat" class="postform">
                    <option value="-1" <?php selected('-1', $selected_category); ?>>All Categories</option>
                    <?php foreach ($terms as $term) : ?>
                        <option class="level-0" value="<?php echo esc_attr($term->term_id); ?>" <?php selected($term->term_id, $selected_category); ?>>
                            <?php echo esc_html($term->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" class="button" value="<?php esc_attr_e('Apply', 'wcc'); ?>">
            </div>
        </form>

    </div>
    <table class="wp-list-table widefat fixed table_view-list striped posts">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Categories</th>
                <th>Tags</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post) :
                $author = get_userdata($post->post_author);
                $categories = get_the_category($post->ID);
                $tags = get_the_tags($post->ID);
                $comments = get_comments(array('post_id' => $post->ID));
                $date = get_the_date('Y/m/d \a\t g:i a', $post->ID);
            ?>
                <tr>
                    <td>
                        <strong><a href="<?php echo get_edit_post_link($post->ID); ?>" class="row-title"><?php echo $post->post_title; ?></a>
                        </strong>
                    </td>
                    <td>
                        <a href="<?php echo get_edit_user_link($post->post_author); ?>"><?php echo $author->display_name; ?></a>
                    </td>
                    <td>

                        <?php
                        foreach ($categories as $category) {
                            echo $category->name . ', ';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        foreach ($tags as $tag) {
                            echo $tag->name . ', ';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        echo '<div class="post-com-count-wrapper">';
                        foreach ($comments as $comment) {
                            $comment_count = get_comment_count($comment->comment_ID)['approved']; // Get the approved comment count
                            $comment_link = get_edit_comment_link($comment->comment_ID); // Get the edit comment link
                            $comment_text = sprintf(_n('%s Comment', '%s Comments', $comment_count, 'wcc'), $comment_count); // Singular or plural form

                            echo '<a class="post-com-count post-com-count-approved" href="' . esc_url($comment_link) . '">
            <span class="comment_count_approved" aria-hidden="true">' . esc_html($comment_count) . '</span>
            <span class="screen-reader-text">' . esc_html($comment_text) . '</span>
            </a>';
                        }
                        echo '</div>';
                        ?>
                    </td>

                    <td>
                        <?php echo $date; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Categories</th>
                <th>Tags</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </tfoot>
    </table>

</div>