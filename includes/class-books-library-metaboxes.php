<?php


/**
 * Implement all metabox for the plugin
 *
 * @link       https://www.manthansparmar.com
 * @since      1.0.0
 *
 * @package    Books_Library
 * @subpackage Books_Library/includes
 */

/**
 * Implement all metabox for the plugin
 *
 * File of shortcode functions
 * @package    Books_Library
 * @subpackage Books_Library/includes
 * @author     Manthan Parmar <manthansparmar7@gmail.com>
 */

class booksCustomMetaboxes {
    private $metaboxes = array(
        array(
            'id'       => 'custom_metabox_author_name',
            'title'    => 'Author Name            ',
            'callback' => 'metaboxCallbackAuthorName'
        ),
        array(
            'id'       => 'custom_publication_date',
            'title'    => 'Publication Date',
            'callback' => 'metaboxCallbackPublicationDate'
        ),
        array(
            'id'       => 'custom_metabox_isbn_num',
            'title'    => 'ISBN Number',
            'callback' => 'metaboxCallbackIsbnNum'
        ),  
        array(
            'id'       => 'custom_metabox_publisher_name',
            'title'    => 'Publisher Name',
            'callback' => 'metaboxCallbackPublisherName'
        ),     
        array(
            'id'       => 'custom_metabox_price',
            'title'    => 'Price',
            'callback' => 'metaboxCallbackPrice'
        )
    );

    public function __construct() {
        // Hook into WordPress initialization
        add_action('add_meta_boxes', array($this, 'addMetaboxes'));
        // Hook to save metabox data
        add_action('save_post', array($this, 'saveMetaboxes'));
    }

    public function addMetaboxes() {
        foreach ($this->metaboxes as $metabox) {
            add_meta_box(
                $metabox['id'],              // Metabox ID
                $metabox['title'],           // Metabox title
                array( $this , $metabox['callback']),   // Callback function to display content
                'books',                      // Post type (e.g., post, page)
                'normal',                    
                'high'                      
            );
        }
    }
    //callback function for author name metabox
    public function metaboxCallbackAuthorName($post) {
        $author_name = get_post_meta($post->ID, '_custom_metabox_author_name', true);
        ?>
        <label for="custom_metabox_author_name"><?php esc_html_e( 'Enter author name: ', 'books-library' ); ?></label>
        <input type="text" id="custom_metabox_author_name" name="custom_metabox_author_name" value="<?php echo esc_attr($author_name); ?>" />
        <?php
    }
    //callback function for publication date metabox
    public function metaboxCallbackPublicationDate($post) {
        $publication_date = get_post_meta($post->ID, '_custom_publication_date', true);
        wp_nonce_field('save_custom_metabox', 'book_detail_page_nonce'); // Add nonce field
        // Use gmdate() to avoid issues with runtime timezone changes
        $max_date = gmdate('Y-m-d');
        ?>
        <label for="custom_publication_date"><?php esc_html_e( 'Enter publication date:', 'books-library' ); ?></label>
        <input type="date" id="custom_publication_date" name="custom_publication_date" value="<?php echo esc_attr($publication_date); ?>" max="<?php echo esc_attr($max_date); ?>">
        <input type="hidden" value="<?php echo esc_attr($publication_date); ?>">
        <?php
    }
    //callback function for ISBN Number metabox
    public function metaboxCallbackIsbnNum($post) {
        $interests = get_post_meta($post->ID, '_custom_metabox_isbn_num', true);
        ?>
        <label for="custom_metabox_isbn_num"><?php esc_html_e( 'Enter ISBN number: ', 'books-library' ); ?></label>
        <input type="text" id="custom_metabox_isbn_num" name="custom_metabox_isbn_num" value="<?php echo esc_attr($interests); ?>" />
        <?php
    }
    //callback function for publisher name completed metabox
    public function metaboxCallbackPublisherName($post) {
        $publisher_name = get_post_meta($post->ID, '_custom_metabox_publisher_name', true);
        ?>
        <label for="custom_metabox_publisher_name"><?php esc_html_e( 'Enter publisher name:', 'books-library' ); ?></label>
        <input type="text" id="custom_metabox_publisher_name" name="custom_metabox_publisher_name" value="<?php echo esc_attr($publisher_name); ?>" min="1" max="10" />
        <?php
    }
    //callback function for total year of price metabox
    public function metaboxCallbackPrice($post) {
        $year_of_price = get_post_meta($post->ID, '_custom_metabox_price', true);
        ?>
        <label for="custom_metabox_price"><?php esc_html_e( 'Enter price: $', 'books-library' ); ?></label>
        <input type="number" id="custom_metabox_price" name="custom_metabox_price" value="<?php echo esc_attr($year_of_price); ?>" min="1" max="30" />
        <?php
    }

    //Save and Update metabox values
    public function saveMetaboxes($postId) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST['book_detail_page_nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['book_detail_page_nonce']), 'save_custom_metabox')) return;

        if (!current_user_can('edit_post', $postId)) return;
        $flage=1;
        foreach ($this->metaboxes as $metabox) {
            $field_name = $metabox['id'];
            if (isset($_POST[$field_name])) {
                update_post_meta($postId, '_' . $field_name, sanitize_text_field($_POST[$field_name]));
            }
        }
    }
}
// Instantiate the class
new booksCustomMetaboxes();