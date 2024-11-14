(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

//Following code is for books listing using ajax
jQuery(document).ready(function() {
    jQuery('.books_education_filter').select2(); // Assuming you are using the Select2 library
    jQuery('.books_technology_filter').select2(); // Assuming you are using the Select2 library
	function books_load_all_posts(page){
		//search filter value
		var books_search_val = '';
		if( jQuery('.search_books').val() != ''){
			books_search_val = jQuery('.search_books').val();
		}
		//number of jobs completed filter value
		var books_num_of_jobs_completed_val = '';
		if( jQuery('.num_of_jobs_completed').val() != ''){
			books_num_of_jobs_completed_val = jQuery('.num_of_jobs_completed').val();
		}
		//number of jobs completed filter value
		var books_years_of_experiance_val = '';
		if( jQuery('.years_of_experiance').val() != ''){
			books_years_of_experiance_val = jQuery('.years_of_experiance').val();
		}
		//age range sider filter value
		var age_range_slider_val = '';
		if( jQuery('.age_range_slider_val').val() != ''){
			age_range_slider_val = jQuery('.age_range_slider_val').val();
		}	
		//star rating filter value
		var stars_rating_val = '';
		if( jQuery('.stars_rating_val').val() != ''){
			stars_rating_val = jQuery('.stars_rating_val').val();
		}
		//skilss filter value
		var selected_genres_vals = jQuery(".books_technology_filter  option:selected").map(function () {
        	return jQuery(this).val();
    	}).get().join(',');
    	//Ajax function
		jQuery.ajax({
			type: 'POST',
			url: frontend_ajax_object.ajaxurl,
			data: { 
				page: page, 
				action: "books_listing_ajax", 
				books_search: books_search_val,
				selected_genres: selected_genres_vals,
				books_num_of_jobs_completed : books_num_of_jobs_completed_val,
				books_years_of_experiance : books_years_of_experiance_val,
				books_stars_rating_val : stars_rating_val,
				books_age_range_slider : age_range_slider_val,
				_ajax_nonce: frontend_ajax_object.nonce // Pass the nonce
			},
			success: function (result) {
				jQuery(".cust_loader").hide();    
				jQuery(".books_universal_container").show();                      									
				jQuery(".books_universal_container").html(result);
			}, 
			beforeSend: function() {
				jQuery(".cust_loader").show();                      				
				jQuery(".books_universal_container").hide();                      				
		    }
		});   
	}
	//Loads all books
  	books_load_all_posts(1);
  	//Pagination click event
	jQuery(document).on("click",".books-universal-pagination li.active",function() {
		var page = jQuery(this).attr('p');
		books_load_all_posts(page);
	});
  	//Form Filter button click event	
	jQuery(".books_filter").submit(function(e){
		e.preventDefault();
  		books_load_all_posts(1);	  
	});
	jQuery(document).on("click",".sort_asc_title",function() {
		jQuery(this).toggleClass("desc_title");
	});
	jQuery(document).on("click",".sort_asc_publcation_date",function() {
		jQuery(this).toggleClass("desc_pdate");
	});
	jQuery(document).on("click",".sort_asc_price",function() {
		jQuery(this).toggleClass("desc_price");
	});

});	