<?php

class Meta_Boxes
{
    public function register()
    {
        add_meta_box('afs_books_meta_box', __('Book Details', 'afs'), [$this, 'books_meta_box_callback'], 'book', 'normal', 'high');
        add_meta_box('afs_chapter_meta_box', __('Chapter Details', 'afs'), [$this, 'chapter_meta_box_callback'], 'chapter', 'normal', 'high');
    }

    public function books_meta_box_callback($post)
    {
        $book_author = get_post_meta($post->ID, 'book_author', true);
        $book_genre = get_post_meta($post->ID, 'book_genre', true);
?>
        <p>
            <label for="book_author">Book Author:</label>
            <input type="text" name="book_author" id="book_author" value="<?php echo esc_attr($book_author); ?>" />
        </p>
        <p>
            <label for="book_genre">Book Genre:</label>
            <input type="text" name="book_genre" id="book_genre" value="<?php echo esc_attr($book_genre); ?>" />
        </p>
    <?php
    }

    public function chapter_meta_box_callback($post)
    {
        $chapter_source = get_post_meta($post->ID, 'chapter_source', true);
        $chapter_number = get_post_meta($post->ID, 'chapter_number', true);
        $books = get_posts(array(
            'post_type' => 'book',
            'posts_per_page' => -1
        ));
    ?>
        <p>
            <label for="chapter_source">Book:</label>
            <select name="chapter_source" id="chapter_source">
                <option value="">Select Book</option>
                <?php foreach ($books as $book) : ?>
                    <option value="<?php echo esc_attr($book->ID); ?>" <?php selected($chapter_source, $book->ID); ?>><?php echo esc_html($book->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="chapter_number">Chapter Number:</label>
            <input type="text" name="chapter_number" id="chapter_number" value="<?php echo esc_attr($chapter_number); ?>" />
        </p>
<?php
    }

    public function save($post_id)
    {
        if (!isset($_POST['post_type'])) {
            return;
        }

        if ($_POST['post_type'] == 'book') {
            if (isset($_POST['book_author'])) {
                update_post_meta($post_id, 'book_author', sanitize_text_field($_POST['book_author']));
            }
            if (isset($_POST['book_genre'])) {
                update_post_meta($post_id, 'book_genre', sanitize_text_field($_POST['book_genre']));
            }
        }

        if ($_POST['post_type'] == 'chapter') {
            if (isset($_POST['chapter_source'])) {
                update_post_meta($post_id, 'chapter_source', sanitize_text_field($_POST['chapter_source']));
            }
            if (isset($_POST['chapter_number'])) {
                update_post_meta($post_id, 'chapter_number', sanitize_text_field($_POST['chapter_number']));
            }
        }
    }
}
