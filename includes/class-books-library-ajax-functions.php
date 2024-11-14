<?php

/**
 * Implement all ajax functions for the plugin
 *
 * @link       https://www.manthansparmar.com
 * @since      1.0.0
 *
 * @package    Books_Library
 * @subpackage Books_Library/includes
 */

/**
 * Implement all ajax functions for the plugin
 *
 * File of AJAX functions
 * @package    Books_Library
 * @subpackage Books_Library/includes
 * @author     Manthan Parmar <manthansparmar7@gmail.com>
 */

class Books_Library_Ajax {
    public function __construct() {
        /* ajax functions for book listing */
        add_action( 'wp_ajax_books_listing_ajax', array( $this, 'books_listing_ajax_callback') );
        add_action( 'wp_ajax_nopriv_books_listing_ajax', array( $this, 'books_listing_ajax_callback') );
    }
    /* ajax for book listing callback */
	function books_listing_ajax_callback() {
    /* check nonce */
        check_ajax_referer('books_listing_nonce', '_ajax_nonce');
	    if(isset($_POST['page'])){
	        $page = sanitize_text_field($_POST['page']);
	        $cur_page = $page;
	        $page -= 1;
	        $per_page = 5;
	        $start = $page * $per_page;   
	        $book_search = '';
	        //search filter
            if ( isset($_POST['books_search']) && $_POST['books_search'] !== '' ) {
                // Sanitize the input
                $book_search = sanitize_text_field($_POST['books_search']);
            }
	        //genres taxonmoy filter
	        $tax_query = array();   
	        if( isset($_POST['selected_genres']) && $_POST['selected_genres'] !==''){  
			    $genresExplodedAry = explode(',', sanitize_text_field($_POST['selected_genres']));	
	            $tax_query[] =  array(
	                'taxonomy' => 'genres',
	                'field' => 'id',
	                'terms' => $genresExplodedAry
	            );            
	        }
	        //number of jobs completed meta field filter	  
	        $meta_query = array();   
	        if( isset($_POST['book_num_of_jobs_completed']) && $_POST['book_num_of_jobs_completed'] !==''){
	            $meta_query[] = array(
                    'key' => '_custom_metabox_num_of_jobs_completed',
                    'value' => sanitize_text_field($_POST['book_num_of_jobs_completed']),
                    'compare' => '='
                );            
	        }
	        //years of experiance meta field filter	  	       
	        if ( isset($_POST['book_years_of_experiance']) && $_POST['book_years_of_experiance'] !=='' ){
	            $meta_query[] =  array(
                    'key' => '_custom_metabox_year_of_experiance',
                    'value' => sanitize_text_field($_POST['book_years_of_experiance']),
                    'compare' => '='
                );
	        }
	        //star rating meta field filter	 
	        if( isset($_POST['book_stars_rating_val']) && $_POST['book_stars_rating_val'] !=='' ){
	            $meta_query[] =  array(
                    'key' => '_custom_metabox_ratings',
                    'value' => sanitize_text_field($_POST['book_stars_rating_val']),
                    'compare' => '='
                );
	        }
			  //age range slider meta field filter
	        if( isset($_POST['book_age_range_slider']) && $_POST['book_age_range_slider'] !=='' ){
	       			$meta_query[] =  array(
                    'key' => 'book_hidden_age',
                    'value' => sanitize_text_field($_POST['book_age_range_slider']),
                    'compare' => '<='
                );
	        }
	        $post_type = 'books'; 
	        //WP Query to fetch book posts based on filters 
	        $all_books_post = new WP_Query(
	            array(
	                'post_type'         => $post_type,
	                'post_status '      => 'publish',
	                's'                 => $book_search,
	                'orderby'           => 'DATE' ,
	                'order'             => 'DESC',
	                'posts_per_page'    => $per_page,
	                'offset'            => $start,
	                'tax_query'         => $tax_query, //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	                'meta_query'        => $meta_query, //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	            )
	        );
	        //WP query result output 
            ?>
          <table class="book_listing">
              <thead>
                  <tr class="table-success">
                      <th><?php esc_html_e( 'No.', 'books-library' ); ?></th>
                      <th><?php esc_html_e( 'Book Title', 'books-library' ); ?>
                        <img src="<?php echo  BOOKS_LIBRARY_URL_PATH . 'assets/images/sort-asc.png'; ?>" class="sort_asc_title sorting_img">
                        <img src="<?php echo  BOOKS_LIBRARY_URL_PATH . 'assets/images/sort-desc.png'; ?>" class="sort_desc sorting_img">
                      </th>
                      <th><?php esc_html_e( 'Author', 'books-library' ); ?></th>
                      <th><?php esc_html_e( 'Publication Date', 'books-library' ); ?>
                        <img src="<?php echo  BOOKS_LIBRARY_URL_PATH . 'assets/images/sort-asc.png'; ?>" class="sort_asc sorting_img">
                        <img src="<?php echo  BOOKS_LIBRARY_URL_PATH . 'assets/images/sort-desc.png'; ?>" class="sort_desc sorting_img">
                      </th>
                      <th><?php esc_html_e( 'Genre', 'books-library' ); ?></th>
                      <th><?php esc_html_e( 'Price', 'books-library' ); ?>
                        <img src="<?php echo  BOOKS_LIBRARY_URL_PATH . 'assets/images/sort-asc.png'; ?>" class="sort_asc sorting_img">
                        <img src="<?php echo  BOOKS_LIBRARY_URL_PATH . 'assets/images/sort-desc.png'; ?>" class="sort_desc sorting_img">  
                    </th>
                  </tr>
              </thead>
              <tbody>
                  <?php if( $all_books_post->have_posts() ) :
                      while ( $all_books_post->have_posts() ) : $all_books_post->the_post(); 
                          $current_post = $all_books_post->current_post + 1;
                          for ( $i = 1 ; $i < 5 ; $i++) { 
                              if( $cur_page > $i){
                                  $current_post = $current_post + $per_page;
                              }
                          }
                          $no_of_jobs_completed = get_post_meta(get_the_ID(), '_custom_metabox_num_of_jobs_completed', true);
                          $no_of_jobs_completed = $no_of_jobs_completed ? $no_of_jobs_completed : '-';

                          $author_name       = get_post_meta(get_the_ID(), '_custom_metabox_author_name', true);
                          $author_name       = $author_name ? $author_name : '-';

                          $publication_date = get_post_meta(get_the_ID(), '_custom_publication_date', true);
                          if ($publication_date) {
                              // Convert the date to a timestamp
                              $timestamp = strtotime($publication_date);
                              // Format the date as '5th Feb, 2003'
                              $formatted_date = date('jS M, Y', $timestamp);
                          } else {
                              $formatted_date = '-';
                          }

                          $book_price = get_post_meta(get_the_ID(), '_custom_metabox_price', true);
                          $book_price = $book_price ? '$' . $book_price : '-';

                          $rating_val = get_post_meta(get_the_ID(), '_custom_metabox_ratings', true);

                          $genres_terms = wp_get_post_terms(get_the_ID(), 'genres');
                          $book_genres = !empty($genres_terms) && !is_wp_error($genres_terms) 
                              ? implode(', ', wp_list_pluck($genres_terms, 'name')) 
                              : '-';
                      ?>
                      <tr class="table-primary">
                          <td><?php echo esc_html($current_post); ?>.</td>
                          <td>
                              <a href="<?php echo esc_url(get_permalink()); ?>" target="_blank">
                                  <?php the_title(); ?>
                              </a>
                          </td>
                          <td><?php echo esc_html( $author_name ); ?></td>
                          <td><?php echo esc_html( $formatted_date ); ?></td>
                          <td><?php echo esc_html( $book_genres ); ?></td>
                          <td><?php echo esc_html(  $book_price ); ?></td>
                  <?php endwhile; wp_reset_postdata(); else: ?>
                      <tr>
                          <td colspan="8"><?php esc_html_e( 'No books available.', 'books-library' ); ?></td>
                      </tr>
                  <?php endif; ?>
              </tbody>
          </table>
        <?php
          $no_of_paginations = ceil($all_books_post->found_posts / $per_page);
          if ($no_of_paginations > 1):
        ?>
          <div class='books-universal-pagination'>
            <ul>
              <?php if ($cur_page > 1): ?>
                <li p='1' class='active'><?php esc_html_e( 'First', 'books-library' ); ?></li>
                <li p='<?php echo esc_attr($cur_page) - 1; ?>' class='active'><?php esc_html_e( 'Previous', 'books-library' ); ?></li>
              <?php else: ?>
                <li class='inactive'><?php esc_html_e( 'First', 'books-library' ); ?></li>
                <li class='inactive'><?php esc_html_e( 'Previous', 'books-library' ); ?></li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $no_of_paginations; $i++): ?>
              <li p='<?php echo esc_attr($i); ?>' class='<?php echo (int) $cur_page === (int) $i ? "selected" : "active"; ?>'>
                <?php echo esc_html($i); ?>
              </li>
              <?php endfor; ?>

              <?php if ($cur_page < $no_of_paginations): ?>
                <li p='<?php echo esc_attr($cur_page) + 1; ?>' class='active'><?php esc_html_e( 'Next', 'books-library' ); ?></li>
                <li p='<?php echo esc_attr($no_of_paginations); ?>' class='active'><?php esc_html_e( 'Last', 'books-library' ); ?></li>
              <?php else: ?>
                <li class='inactive'><?php esc_html_e( 'Next', 'books-library' ); ?></li>
                <li class='inactive'><?php esc_html_e( 'Last', 'books-library' ); ?></li>
              <?php endif; ?>
            </ul>
          </div>
          <?php 
          endif; 
	    }
	    // Always exit to avoid further execution
	    exit();
	}
}
//initialize our Books_Library_Ajax for ajax functionalities 
new Books_Library_Ajax();
?>