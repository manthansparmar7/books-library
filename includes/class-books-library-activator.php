<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.manthansparmar.com
 * @since      1.0.0
 *
 * @package    Books_Library
 * @subpackage Books_Library/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Books_Library
 * @subpackage Books_Library/includes
 * @author     Manthan Parmar <manthansparmar7@gmail.com>
 */

class Books_Library_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public $posttype_slug = 'books';

    public function __construct() {
        // Hook into the activation and deactivation actions
        register_activation_hook(__FILE__, array($this, 'activate'));
        // Hook into the init action to register custom post type
        add_action('init', array($this, 'registerPostTypeBooks'));
        // Hook into the init action to register custom taxonomy
        add_action('init', array($this, 'registerTaxonomyGenres'));
        
        add_filter('manage_edit-books_columns', [$this, 'add_custom_columns']);
        add_action('manage_books_posts_custom_column', [$this, 'populate_custom_columns'], 10, 2);
        add_filter('manage_edit-books_sortable_columns', [$this, 'make_columns_sortable']);
        add_action('pre_get_posts', [$this, 'handle_sorting_logic']);
        add_action('restrict_manage_posts', [$this, 'add_genres_filter_dropdown']);
        add_action('widgets_init', [$this, 'register_books_widget']);
        add_action('quick_edit_custom_box', [$this, 'add_quick_edit_fields'], 10, 2);
        add_action('save_post', [$this, 'save_quick_edit_data']);
    }

    public function activate() {
        // Code to run on plugin activation
        $this->registerPostTypeBooks();
        $this->registerTaxonomyGenres();
    }
    
    // register custom post type callback function
    public function registerPostTypeBooks() {
        $singular_posttype_name = 'Book';
        $plural_posttype_name = 'Books';
        $labels = array(
        'name'                  => _x( $plural_posttype_name, 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( $singular_posttype_name, 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( $plural_posttype_name, 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x(  $singular_posttype_name, 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New ' . $singular_posttype_name , 'textdomain' ),
        'new_item'              => __( 'New '. $singular_posttype_name, 'textdomain' ),
        'edit_item'             => __( 'Edit '. $singular_posttype_name, 'textdomain' ),
        'view_item'             => __( 'View '. $singular_posttype_name, 'textdomain' ),
        'all_items'             => __( 'All ' .  $plural_posttype_name, 'textdomain' ),
        'search_items'          => __( 'Search ' .  $plural_posttype_name, 'textdomain' ),
        'parent_item_colon'     => __( 'Parent : '.  $plural_posttype_name, 'textdomain' ),
        'not_found'             => __( 'No ' . $plural_posttype_name . ' found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No ' . $plural_posttype_name . ' found in Trash.', 'textdomain' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $this->posttype_slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-book-alt',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        );
        register_post_type(  $this->posttype_slug , $args );
    }

    // register custom taxonomy callback function
    public function registerTaxonomyGenres() {
        $taxonomy_slug = 'genres';
        $singular_taxonomy_name = 'Genre';
        $plural_taxonomy_name = 'Genres';
        $labels = array(
            'name'              => __( $plural_taxonomy_name , 'text-domain'),
            'singular_name'     => __( $singular_taxonomy_name , 'text-domain'),
            'search_items'      => __('Search ' .  $plural_taxonomy_name, 'text-domain'),
            'all_items'         => __('All ' .  $plural_taxonomy_name, 'text-domain'),
            'parent_item'       => __('Parent ' . $singular_taxonomy_name, 'text-domain'),
            'parent_item_colon' => __('Parent ' . $singular_taxonomy_name . ':', 'text-domain'),
            'edit_item'         => __('Edit ' . $singular_taxonomy_name, 'text-domain'),
            'update_item'       => __('Update ' . $singular_taxonomy_name, 'text-domain'),
            'add_new_item'      => __('Add New ' . $singular_taxonomy_name, 'text-domain'),
            'new_item_name'     => __('New '  .$singular_taxonomy_name . ' Name', 'text-domain'),
            'menu_name'         => __( $plural_taxonomy_name . '', 'text-domain'),
        );

        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => $taxonomy_slug ),
        );
        register_taxonomy( $taxonomy_slug , array( $this->posttype_slug ), $args);
    }

 // Method to add new columns to the Books admin table
    public function add_custom_columns($columns) {
        unset($columns['date']); // Remove the default 'date' column
        
        $columns['author_name'] = 'Author Name';
        $columns['publication_date'] = 'Publication Date';
        $columns['genres'] = 'Genres';

        // Optionally, re-add the 'date' column at the end if needed
        $columns['date'] = 'Date';

        return $columns;
    }

    // Method to display content in the custom columns
    public function populate_custom_columns($column, $post_id) {
        switch ($column) {
            case 'author_name':
                $author_name = get_post_meta($post_id, '_custom_metabox_author_name', true);
                echo esc_html($author_name ? $author_name : '—');
                break;

            case 'publication_date':
                $publication_date = get_post_meta($post_id, '_custom_publication_date', true);
                echo esc_html($publication_date ? date('F j, Y', strtotime($publication_date)) : '—');
                break;

            case 'genres':
                $terms = get_the_terms($post_id, 'genres');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $genre_names = wp_list_pluck($terms, 'name');
                    echo esc_html(join(', ', $genre_names));
                } else {
                    echo '—';
                }
                break;
        }
    }

    // Method to make the custom columns sortable
    public function make_columns_sortable($columns) {
        $columns['author_name'] = 'author_name';
        $columns['publication_date'] = 'publication_date';
        return $columns;
    }

    // Method to handle sorting logic for custom columns
    public function handle_sorting_logic($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');
        
        if ('author_name' === $orderby) {
            $query->set('meta_key', '_custom_metabox_author_name');
            $query->set('orderby', 'meta_value');
        }

        if ('publication_date' === $orderby) {
            $query->set('meta_key', '_custom_publication_date');
            $query->set('orderby', 'meta_value');
        }
    }

    // Method to add a dropdown filter for Genres
    public function add_genres_filter_dropdown() {
        global $typenow;
        if ($typenow == 'books') {
            $taxonomy = 'genres';
            $genres = get_terms($taxonomy);
            if (!empty($genres) && !is_wp_error($genres)) {
                $current_genre = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                echo '<select name="' . esc_attr($taxonomy) . '" id="' . esc_attr($taxonomy) . '" class="postform">';
                echo '<option value="">' . __('Show all Genres', 'textdomain') . '</option>';
                foreach ($genres as $genre) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr($genre->slug),
                        selected($current_genre, $genre->slug, false),
                        esc_html($genre->name)
                    );
                }
                echo '</select>';
            }
        }
    }

     // Method to add custom fields to the Quick Edit section
     public function add_quick_edit_fields($column_name, $post_type) {
        if ($post_type !== 'books') {
            return;
        }

        if ($column_name === 'author_name' || $column_name === 'publication_date') {
            ?>
            <fieldset class="inline-edit-col">
                <div class="inline-edit-col">
                    <label>
                        <span class="title"><?php echo ucfirst(str_replace('_', ' ', $column_name)); ?></span>
                        <span class="input-text-wrap">
                            <input type="text" name="<?php echo esc_attr($column_name); ?>" value="">
                        </span>
                    </label>
                </div>
            </fieldset>
            <?php
        }
    }

    // Method to save the data from Quick Edit
    public function save_quick_edit_data($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = ['_custom_metabox_author_name', '_custom_publication_date'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    // Method to register the widget
    public function register_books_widget() {
        register_widget('RecentBooksWidget');
    }
}

class RecentBooksWidget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'recent_books_widget',
            __('Recent Books', 'textdomain'),
            ['description' => __('Displays a list of recent books', 'textdomain')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $num_books = !empty($instance['num_books']) ? absint($instance['num_books']) : 5;
        $show_price = !empty($instance['show_price']);
        
        $recent_books = new WP_Query([
            'post_type' => 'books',
            'posts_per_page' => $num_books,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        
        if ($recent_books->have_posts()) {
            echo '<ul class="recent-books-list">';
            while ($recent_books->have_posts()) {
                $recent_books->the_post();
                echo '<li>';
                echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                
                if ($show_price) {
                    $price = get_post_meta(get_the_ID(), 'price', true);
                    if ($price) {
                        echo ' - ' . esc_html('$' . number_format($price, 2));
                    }
                }
                
                echo '</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No recent books found.', 'textdomain') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Books', 'textdomain');
        $num_books = !empty($instance['num_books']) ? absint($instance['num_books']) : 5;
        $show_price = !empty($instance['show_price']);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'textdomain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('num_books')); ?>"><?php _e('Number of books to display:', 'textdomain'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_books')); ?>" name="<?php echo esc_attr($this->get_field_name('num_books')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($num_books); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php _e('Display book price?', 'textdomain'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['num_books'] = (!empty($new_instance['num_books'])) ? absint($new_instance['num_books']) : 5;
        $instance['show_price'] = !empty($new_instance['show_price']);
        return $instance;
    }
}

// Instantiate the class
new Books_Library_Activator();