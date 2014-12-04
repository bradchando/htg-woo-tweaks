	<?php
	/*
	Plugin Name: HTG Woo Tweaks
	Description: Extends the functionality of basic WordPress and WooCommerce to meet the needs of the Hometown Giving business model
	Version: 1.0
	Author: Brad Chandonnet
	Author URI: http://bradchandonnet.com
	License: GPLv2
	*/
	
	//This function disallows the Processing Fee if a business membership is in the cart
		
	add_filter( "woocommerce_pay4pay_apply", 'htg_allow_fee' , 6 , 0 );
	
	function htg_allow_fee() {
		
		global $woocommerce;
		
		$htg_bus_membership_id = 313; //This is the ID of the membership product
		 
		foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {
	        
			$_product = $values['data'];
		
			if( $htg_bus_membership_id == $_product->id ) {		//A business membership is in the cart
	            
	            return false; //Do not charge the fee
	            
    		}
    	
    	}
    		     
	    return true; //Charge the fee
	}
	
	
	//This function hides any memberships from the shop page
	
	add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
 
	function custom_pre_get_posts_query( $q ) {
	 
		if ( ! $q->is_main_query() ) return;
		
		if ( ! $q->is_post_type_archive() ) return;
		
		if ( ! is_admin() && is_shop() ) {
		 
			$q->set( 'tax_query', array(array(
				
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => array( 'memberships' ),  // Don't display products in the memberships category on the shop page
				'operator' => 'NOT IN'
			
			)));
			
		}
	 
		remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
	 
	}


/*
* This is code that will be addapted to change display of specific add to cart buttons.
*
* 	// Change Add To Cart Text
*	add_filter( 'single_add_to_cart_text', 'yoursite_single_cart_text', 10, 1 );
*	function yoursite_single_cart_text( $button_text ) {
*	$button_text = 'Add Me Baby!';
*	return $button_text;
*	} 
*
*/