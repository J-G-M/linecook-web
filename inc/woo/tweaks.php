<?php if (!defined('ABSPATH')) {
	exit;
}


/**
 * https://docs.woothemes.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );



/**
 * Manage WooCommerce styles and scripts.
 * @link http://gregrickaby.com/remove-woocommerce-styles-and-scripts/
 */
add_action( 'wp_enqueue_scripts', function () {

	// Remove the generator tag
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

	// Remove select2 scripts
	wp_dequeue_style( 'select2' );
	wp_dequeue_style( 'woocommerce_chosen_styles' );
	wp_deregister_style( 'select2' );

	wp_dequeue_script( 'select2');
	wp_dequeue_script( 'wc-chosen' );
	wp_deregister_script('select2');

  /**
   * Unless we're in the store, remove all the cruft!
   */
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) :

		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce-general');
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_style( 'select2' );
		wp_dequeue_script( 'wc-add-payment-method' );
		wp_dequeue_script( 'wc-lost-password' );
		wp_dequeue_script( 'wc_price_slider' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-add-to-cart' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-credit-card-form' );
		wp_dequeue_script( 'wc-checkout' );
		wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-cart' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'jquery-blockui' );
		wp_dequeue_script( 'jquery-placeholder' );
		wp_dequeue_script( 'jquery-payment' );
		wp_dequeue_script( 'fancybox' );
		wp_dequeue_script( 'jqueryui' );
	endif;
}, 99 );


/**
 * WooCommerce 3.0 gallery fix
 */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
});



/**
 * Removes the "shop" title on the main shop page
 */
add_filter( 'woocommerce_show_page_title' , function () {
	return false;
});


remove_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_filter( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_filter( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_filter( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);


add_filter( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5);
add_filter( 'woocommerce_single_product_summary', function() { ?>
	<div class="entry-summary">
		<?php the_content(); ?>
	</div>
<?php }, 20);

add_filter( 'woocommerce_after_single_product', function() {

	$abt_title = get_post_meta( get_the_ID(), 'wi_section_title', true);
	$abt_text  = get_post_meta( get_the_ID(), 'wi_content', true);

	if ( ! $abt_title && ! $abt_text )
		return; ?>

	<div class="entry-details">
		<?php if ($abt_title) : ?>
			<h3 class="page-title"><?= $abt_title; ?></h3>
		<?php endif;

		if ( $abt_text )
			echo wpautop( $abt_text ); ?>
	</div>
<?php }, 10);



/**
 * Checkout customization
 */
add_filter( 'woocommerce_checkout_fields', function( $fields ) {

	$fields['billing']['billing_state']['required'] = 0;
	$fields['billing']['billing_phone']['required'] = 0;

	unset( $fields['billing']['billing_state']['validate'] );
	unset( $fields['billing']['billing_state'] );
	unset( $fields['billing']['billing_company'] );
	unset( $fields['billing']['billing_country'] );
	unset( $fields['billing']['billing_city'] );


	$fields['shipping']['shipping_state']['required'] = 0;

	unset( $fields['shipping']['shipping_state']['validate'] );
	unset( $fields['shipping']['shipping_state'] );
	unset( $fields['shipping']['shipping_company'] );

	return $fields;
});


/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_checkout_billing_form', function ( $checkout ) {

	$locations = get_posts( ['post_type' => 'location', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'] );

	if ( $locations ) {
		foreach ($locations as $location) {
			$times = NF()->get_pickup_time($location->ID);

			$select_loc[$location->ID] = apply_filters( 'the_title', $location->post_title );
			$location_data[$location->ID] = $times;

		}
	}

	ob_start(); ?>

	<div id="pickup_location_wrap">
		<h2><?= __('Pickup Information'); ?></h2>

		<div class="row">

			<p class="span-12">
				<label>Pickup Location</label>
				<span class="input-select">
					<select name="pickup_location" id="pickup_location">
						<option value="0">Select Location</option>
						<?php foreach ( $select_loc as $id => $title ) : ?>
							<option value="<?= $id; ?>" data-id="<?= $id; ?>"><?= $title; ?></option>
						<?php endforeach; ?>
					</select>
				</span>
			</p>

			<p class="span-6">
				<label>Pickup Day</label>
				<span class="input-select">
					<select name="pickup_day" id="pickup_day">
						<option value="0">Select Day</option>
					</select>
				</span>
			</p>

			<p class="span-6">
				<label>Pickup Time</label>
				<span class="input-select">
					<select name="pickup_time" id="pickup_time">
						<option value="0">Select Time</option>
					</select>
				</span>
			</p>
		</div>

		<input type="hidden" name="location_data" id="location_data" value="<?= htmlspecialchars(json_encode($location_data)); ?>">

	</div>

	<?php echo ob_get_clean();

});


/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', function () {

	if ( ! $_POST['pickup_location']  || ! $_POST['pickup_day'] || ! $_POST['pickup_time'] )
		wc_add_notice( __( 'Please Select Pickup location, date and time.' ), 'error' );
});


/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', function ( $order_id ) {

	$location    = isset($_POST['pickup_location']) ? intval($_POST['pickup_location']) : false;
	$pickup_day  = isset($_POST['pickup_day']) ? sanitize_text_field($_POST['pickup_day']) : '';
	$pickup_time = isset($_POST['pickup_time']) ? sanitize_text_field($_POST['pickup_time']) : '';
	$pickup      = trim( $pickup_day . ' ' . $pickup_time ) ;

	if ( ! empty( $location ) ) {
		update_post_meta( $order_id, 'pickup_location',  $location );
	}

	if ( ! empty( $pickup ) ) {
		update_post_meta( $order_id, 'pickup_day_time',  $pickup );
	}
});


/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', function ($order){

	$location = get_post_meta( $order->id, 'pickup_location', true );
	$pickup   = get_post_meta( $order->id, 'pickup_day_time', true );

	if ( $location ) {
		$post = get_post($location);

		echo '<p><strong>'.__('Pickup Location').':</strong> ' . apply_filters( 'the_title', $post->post_title ) . '</p>';
	}


	if ( $pickup ) {
		echo '<p><strong>'.__('Pickup Day and Time').':</strong> ' . apply_filters( 'the_title', $pickup ) . '</p>';
	}


}, 10, 1 );


/**
 * Adding custom fields to emails
 */
add_filter('woocommerce_email_order_meta_keys', function ( $keys ) {

	$keys[] = 'pickup_location';
	$keys[] = 'pickup_day_time';

	return $keys;
});