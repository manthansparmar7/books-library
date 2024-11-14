<?php

/**
 * Implement all shortcode functions for the plugin
 *
 * @link       https://www.manthansparmar.com
 * @since      1.0.0
 *
 * @package    Books_Library
 * @subpackage Books_Library/includes
 */

/**
 * Implement all shortcode functions for the plugin
 *
 * File of shortcode functions
 * @package    Books_Library
 * @subpackage Books_Library/includes
 * @author     Manthan Parmar <manthansparmar7@gmail.com>
 */

class Books_Library_Shortcodes {
	public function __construct() {
		// Shortcode for CPT -> Books listing with optional parameters
		add_shortcode('book_list', array($this, 'book_listing_shortcode_callback'));
	}	
	// Callback function for books listing shortcode
	function book_listing_shortcode_callback($atts) {
		// Define and extract shortcode attributes
		$atts = shortcode_atts(
			array(
				'genres'      => '',
				'author_name' => ''
			),
			$atts,
			'book_list'
		);
		$genres_filter 		= !empty($atts['genres']) ? explode(',', $atts['genres']) : array();
		$author_name_filter = !empty($atts['author_name']) ? sanitize_text_field($atts['author_name']) : '';

		ob_start(); ?>
	
		<div class="container">
			<!-- Grid column -->
			<div class="col-md-12 col-xl-12">
				<form name="books_filter" class="books_filter">
					<!-- Add hidden input fields -->
					<input type="hidden" name="hidden_genres_filter" class="hidden_genres_filter" value="<?php echo json_encode($atts['genres']); ?>">
					<input type="hidden" name="hidden_author_name_filter" class="hidden_author_name_filter" value="<?php echo $author_name_filter; ?>">
					<!-- Grid row -->
					<div class="row">
						<!-- Grid column for Genres -->
						<div class="col-md-12">
							<div class="md-form">
								<?php  
								$technology_terms = get_terms(array(
									'taxonomy' => 'genres',
									'hide_empty' => false,
								));
								$technology_ids = wp_list_pluck($technology_terms, 'term_id'); 	
								if (!empty($technology_ids)) { ?> 
									<label><?php esc_html_e('Search by Genres', 'books-library'); ?></label>
									<select name="books_technology_filter" class="form-control books_technology_filter" multiple> 
										<?php foreach ($technology_ids as $technology) { ?>
											<option value="<?php echo esc_attr($technology); ?>" <?php echo in_array($technology, $genres_filter) ? 'selected' : ''; ?>>
												<?php echo esc_html(get_term($technology)->name); ?>
											</option> 
										<?php } ?> 
									</select> 
									<in>
								<?php } ?>
							</div>
						</div>
					</div>
					<!-- Grid row -->
					<div class="row">
						<div class="col-md-12">
							<div class="md-form">
								<br />
								<input type="submit" class="books_filter_submit" value="<?php esc_attr_e('SEARCH', 'books-library'); ?>">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="books_universal_container"></div>
		<div class="cust_loader"></div>
	
		<?php
		return ob_get_clean();
	}	
}
//initialize our Books_Library_Shortcodes
new Books_Library_Shortcodes();
?>