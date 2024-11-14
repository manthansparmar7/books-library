<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.manthansparmar.com
 * @since             1.0.0
 * @package           Books_Library
 *
 * @wordpress-plugin
 * Plugin Name:       Books Library
 * Description:       This plugin provides feature to show book listing page with search and sorting functionality. This plugin will help users to search and sort books based on user's criteria.
 * Version:           1.0.0
 * Author:            Manthan Parmar
 * Author URI:        https://www.manthansparmar.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       books-library
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOKS_LIBRARY_VERSION', '1.0.0' );

define( 'BOOKS_LIBRARY_DIR_PATH', plugin_dir_path( __FILE__ ) );

define( 'BOOKS_LIBRARY_URL_PATH', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-books-library-activator.php
 */
require_once BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library-activator.php';
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-books-library-deactivator.php
 */
require_once BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library-deactivator.php';
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library.php';

/*file that contains metaboxs used in plugin*/
require BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library-metaboxes.php';

/*file that contains shortcodes used in plugin */
require BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library-shortcodes.php';

/*file that contains AJAX functions used in plugin */
require BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library-ajax-functions.php';

/*file that contains common functions used in plugin */
require BOOKS_LIBRARY_DIR_PATH . 'includes/class-books-library-functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_books_library() {

	$plugin = new Books_Library();
	$plugin->run();

}
run_books_library();