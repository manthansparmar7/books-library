<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.manthansparmar.com
 * @since      1.0.0
 *
 * @package    Books_Library
 * @subpackage Books_Library/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Books_Library
 * @subpackage Books_Library/includes
 * @author     Manthan Parmar <manthansparmar7@gmail.com>
 */
class Books_Library_Deactivator {

	/**
	 * The slug for the custom post type.
	 *
	 * @since    1.0.0
	 */
	public $posttype_slug = 'books';

    /**
     * Constructor to hook into deactivation action.
     */
    public function __construct() {
        // Hook into the deactivation action
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Code to run on plugin deactivation.
     * This function no longer flushes rewrite rules.
     *
     * @since    1.0.0
     */
    public function deactivate() {
        $this->deregisterCustomPostType();
        // No flush_rewrite_rules() call here
    }

    /**
     * Deregister the custom post type.
     *
     * @since    1.0.0
     */
    public function deregisterCustomPostType() {
        unregister_post_type($this->posttype_slug);
    }
}

// Instantiate the class Books_Library_Deactivator
new Books_Library_Deactivator();