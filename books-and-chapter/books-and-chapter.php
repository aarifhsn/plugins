<?php

/**
 * Plugin Name: Books and Chapter
 * Plugin URI: http://wordpress.org/plugins/books-and-chapter/
 * Description: Add a chapter and book to your posts
 * Version: 1.0
 * Author: Arif
 * Author URI: http://arif.me
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: books-and-chapter
 * Domain Path: /languages
 */

class Books_And_Chapter
{

    public function __construct()
    {
        add_action('init', [$this, 'initialize']);
    }

    public function initialize()
    {
        $this->afs_books_post_type();
        $this->afs_chapter_post_type();
        add_action('add_meta_boxes', array($this, 'afs_books_and_chapter_meta_box'));
        add_action('save_post', array($this, 'afs_books_save_meta_box'));
        add_filter('the_content', array($this, 'afs_show_chapter_in_books_content'));
        add_filter('the_content', array($this, 'afs_show_books_in_chapter'));
        //add_action('admin_menu', array($this, 'afs_move_chapter_to_book'));
        add_filter('manage_book_posts_columns', array($this, 'afs_add_custom_columns_to_book'), 10, 1);
        add_action('manage_book_posts_custom_column', array($this, 'afs_manage_posts_book_column'), 10, 2);
        add_filter('manage_chapter_posts_columns', array($this, 'afs_add_custom_columns_to_chapter'), 10, 1);
        add_action('manage_chapter_posts_custom_column', array($this, 'afs_manage_posts_chapter_column'), 10, 2);
    }

    public function afs_books_post_type()
    {

        $args = array(
            'labels' =>  array(
                'name' => __('Books', 'books-and-chapter'),
                'singular_name' => __('Book', 'books-and-chapter'),
                'add_new' => __('Add Book', 'books-and-chapter'),
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'book', 'with_front' => false),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest' => true,

        );
        register_post_type('book', $args);
    }

    public function afs_chapter_post_type()
    {

        $args = array(
            'labels' =>  array(
                'name' => __('Chapters', 'books-and-chapter'),
                'singular_name' => __('Chapter', 'books-and-chapter'),
                'add_new' => __('Add Chapter', 'books-and-chapter'),
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'chapter', 'with_front' => false),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest' => true,

        );
        register_post_type('chapter', $args);
    }

    // public function afs_move_chapter_to_book()
    // {
    //     remove_menu_page('edit.php?post_type=chapter');
    //     add_submenu_page('edit.php?post_type=book', 'Chapters', 'All Chapters', 'edit_posts', 'edit.php?post_type=chapter');
    // }

    public function afs_books_and_chapter_meta_box()
    {

        add_meta_box('afs_books_meta_box', __('Book Details', 'books-and-chapter'), array($this, 'afs_books_meta_box_callback'), 'book', 'normal', 'high');

        add_meta_box('afs_chapter_meta_box', __('Chapter Details', 'books-and-chapter'), array($this, 'afs_chapter_meta_box_callback'), 'chapter', 'normal', 'high');
    }

    public function afs_books_meta_box_callback($post)
    {
        $book_author = get_post_meta($post->ID, 'book_author', true);
        $book_genre = get_post_meta($post->ID, 'book_genre', true);

?>

        <p>
            <label for="book_author">Book Author:</label>
            <input type="text" name="book_author" id="book_author" value="<?php echo $book_author; ?>" />
        </p>


        <p>
            <label for="book_genre">Book Genre:</label>
            <input type="text" name="book_genre" id="book_genre" value="<?php echo $book_genre; ?>" />
        </p>

    <?php

    }

    public function afs_chapter_meta_box_callback($post)
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
                    <option value="<?php echo $book->ID; ?>" <?php selected($book->ID, $chapter_source); ?>>
                        <?php echo $book->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="chapter_number">Chapter Number:</label>
            <input type="number" name="chapter_number" id="chapter_number" value="<?php echo $chapter_number; ?>" />
        </p>
<?php }

    public function afs_books_save_meta_box($post_id)
    {

        if (isset($_POST['book_author'])) {
            update_post_meta($post_id, 'book_author', $_POST['book_author']);
        }
        if (isset($_POST['book_genre'])) {
            update_post_meta($post_id, 'book_genre', $_POST['book_genre']);
        }
        if (isset($_POST['chapter_source'])) {
            update_post_meta($post_id, 'chapter_source', $_POST['chapter_source']);
        }
        if (isset($_POST['chapter_number'])) {
            update_post_meta($post_id, 'chapter_number', $_POST['chapter_number']);
        }
    }

    public function afs_show_chapter_in_books_content($content)
    {
        // check if post type is book and has chapters
        if (is_singular('book')) {
            $book_id = get_the_ID();
            $chapters = get_posts(array(
                'post_type' => 'chapter',
                'meta_query'    => array(
                    array(
                        'key' => 'chapter_source',
                        'value' => $book_id,
                        'compare' => '='
                    )
                ),
                'meta_key'  =>  'chapter_number',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'posts_per_page' => -1
            ));
            if (!empty($chapters)) {
                $heading = '<h3>Chapters</h3>';
                $content .= $heading;
                $content .= '<ul>';
                foreach ($chapters as $chapter) {
                    $content .= '<li><a href="' . get_permalink($chapter->ID) . '">' . $chapter->post_title . '</a></li>';
                }
                $content .= '</ul>';
            }
        }
        return $content;
    }


    public function afs_show_books_in_chapter($content)
    {

        if (is_singular('chapter')) {
            $chapter_id = get_the_ID();
            $book_id = get_post_meta($chapter_id, 'chapter_source', true);
            $books = get_post($book_id);
            $thumbnail = get_the_post_thumbnail($book_id, 'thumbnail');
            $heading = '<h3>Book</h3>';
            $thumbnail_html = '<p><a href="' . get_permalink($book_id) . '">' . $thumbnail . '</a></p>';
            $content .= $heading;
            $content .= $thumbnail_html;
            $content .= '<h2><a href="' . get_permalink($book_id) . '">' . $books->post_title . '</a></h2>';
        }
        return $content;
    }



    // add custom columns (thumbnails and chapter number) to book post type
    public function afs_add_custom_columns_to_book($columns)
    {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key == 'cb') {
                $new_columns['thumbnail'] = 'Thumbnail';
            } elseif ($key == 'title') {
                $new_columns['chapter_number'] = 'Chapters';
            }
        }
        return $new_columns;
    }

    public function afs_manage_posts_book_column($column, $post_id)
    {
        if ($column == 'thumbnail') {
            $book_id = get_post_meta($post_id, 'chapter_source', true);
            $thumbnail = get_the_post_thumbnail($book_id, 'thumbnail');
            echo $thumbnail;
        } elseif ($column == 'chapter_number') {
            $chapter_number = get_post_meta($post_id, 'chapter_number', true);
            echo $chapter_number;
        }
    }

    public function afs_add_custom_columns_to_chapter($columns)
    {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key == 'title') {
                $new_columns['book'] = 'Book';
            }
        }
        return $new_columns;
    }

    public function afs_manage_posts_chapter_column($column, $post_id)
    {
        if ($column == 'book') {
            $book_id = get_post_meta($post_id, 'chapter_source', true);
            $book = get_post($book_id);
            echo $book->post_title;
        }
    }

    // remove chapter page default pagination and create a custom one
    public function afs_remove_chapter_page_pagination()
    {
        if (is_singular('chapter')) {
            remove_action('wp_loaded', 'twentytwentyfour_page_pagination');
        }
    }
}

new Books_And_Chapter();
