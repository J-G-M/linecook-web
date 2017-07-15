<?php
/**
 * Custom Emails
 *
 *
 * @author  Vlado Bosnjak
 * @link    https://www.bobz.co
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class NF_Email {


	/**
	 * System Email Notifications
	 *
	 * @param $type Email Type
	 *
	 * 1: Password changed
	 * 2:
	 *
	 *
	 */
	public static function CustomerNotify( $type = NULL, $order = NULL, $user_id = NULL ) {

		if ( ! $order )
			return;


		global $nf;

		$key_subject = 'mail_' . sprintf("%02d", $type) . '_subject';
		$key_content = 'mail_' . sprintf("%02d", $type) . '_content';

		$subject = get_key($key_subject, $nf);
		$content = get_key($key_content, $nf);


		$to      = $order->get_billing_email();
		$subject = self::parse_shortcodes($subject, $order);
		$headers = self::get_headers();


		/**
		 * Parse email body message
		 */
		ob_start();

			get_template_part( '/templates/email/header' );
			echo self::parse_shortcodes($content, $order);
			get_template_part( '/templates/email/footer' );

		$message = ob_get_clean();


		// Send
		$send = wp_mail( $to, $subject, $message, $headers );
	}






	/**
	 *
	 *
	 *
	 * H E L P E R S
	 *
	 *
	 */


	/**
	 * Get email headers
	 */
	public static function get_headers() {

		$separator = md5(time());
		$eol       = PHP_EOL;

		$headers[] = 'From: '. get_option('woocommerce_email_from_name') .'<'. get_option('woocommerce_email_from_address') .'>';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		return $headers;
	}


	/**
	 * Parse Email shortcodes
	 */
	public static function parse_shortcodes( $content, $order = false, $user = false ) {

		global $nf;

		$order_id = $order->get_id();
		$location = get_post_meta( $order_id, 'pickup_location', true );
		$pickup   = date('Y-m-d H:i', get_post_meta( $order_id, 'pickup_day_time', true ));
		$post     = get_post($location);

		$shortcodes = [
			'{order_data}',
			'{user_name}',
			'{user_firstname}',
			'{user_lastname}',
			'{user_email}',
			'{url_website}',
			'{url_login}',
			'{url_ourmenu}',
			'{day_of_week}',
			'{pickup_location_name}',
			'{pickup_location_datetime}',
			'{pickup_location_address}',
		];

		foreach( $shortcodes as $k => $sc ) :

			if ( strpos($content, $sc) === false ) :

				continue;
    		endif;

			switch ( $sc ) :

				case '{order_data}':

					ob_start(); ?>

						<div id="shop_table">
							<table>
								<thead>
									<tr>
										<th>Item</th>
										<th>Quantity</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
									<?php echo wc_get_email_order_items( $order, array(
										'show_sku'      => false,
										'show_image'    => false,
										'image_size'    => array( 32, 32 ),
										'plain_text'    => false,
										'sent_to_admin' => false,
									) ); ?>
								</tbody>
							</table>
						</div>
					<?php
					$data    = ob_get_clean();
					$content = str_replace('{order_data}', $data, $content );
				break;


				case '{user_name}':

					$name    = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
					$content = str_replace('{user_name}', $name, $content );
				break;


				case '{user_firstname}':

					$content = str_replace('{user_firstname}', $order->get_billing_first_name(), $content );
				break;


				case '{user_lastname}':

					$content = str_replace('{user_lastname}', $order->get_billing_last_name(), $content );
				break;


				case '{user_email}':

					$content = str_replace('{user_email}', $order->get_billing_email(), $content );
				break;


				case '{url_website}':

					$content = str_replace('{url_website}', site_url(), $content );
				break;


				case '{url_login}':

					$page_id = get_option('woocommerce_myaccount_page_id');
					$content = str_replace('{url_login}', get_permalink($page_id), $content );
				break;


				case '{url_ourmenu}':

					$content = str_replace('{url_ourmenu}', get_permalink($nf['page_menu']), $content );
				break;


				case '{day_of_week}':

					$date    = new DateTime('now', new DateTimeZone('America/New_York'));
					$content = str_replace('{day_of_week}', $date->format('l'), $content );
				break;


				case '{pickup_location_name}':

					$content = str_replace('{pickup_location_name}', $post->post_title, $content );
				break;

				case '{pickup_location_address}':

					$content = str_replace('{pickup_location_address}', $post->post_content, $content );
				break;


				case '{pickup_location_datetime}':

					$date    = new DateTime($pickup, new DateTimeZone('America/New_York'));
					$content = str_replace('{pickup_location_datetime}', $date->format('l, F d Y H:i'), $content );
				break;

			endswitch;
		endforeach;

		return $content;
	}
}