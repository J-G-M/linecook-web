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
	public static function SystemNotification( $type = NULL, $order_id = NULL, $user_id = NULL ) {

		if ( ! $user_id || ! $order_id )
			return;

		global $nf;

		$user    = new NF_User($user_id);
		$headers = self::get_headers();

		$key_subject = 'mail_' . sprintf("%02d", $type) . '_subject';
		$key_content = 'mail_' . sprintf("%02d", $type) . '_content';

		$subject = get_key($key_subject, $nf);
		$content = get_key($key_content, $nf);


		$to      = $user->data->user_email;
		$subject = self::parse_shortcodes($subject, $order, $user);


		/**
		 * Parse email body message
		 */
		ob_start();

			get_template_part( '/templates/email/header' );
			echo self::parse_shortcodes($content, $order, $user);
			get_template_part( '/templates/email/footer' );

		$message = ob_get_clean();


		// Send
		wp_mail( $to, $subject, $message, $headers );
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

		$headers[] = "From: ". get_option('woocommerce_email_from_address');
		$headers[] = "MIME-Version: 1.0";
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		return $headers;
	}


	/**
	 * Parse Email shortcodes
	 */
	public static function parse_shortcodes( $content, $order = false, $user = false ) {

		global $nf;

		$shortcodes = [
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
				case '{user_name}':

					$content = str_replace('{user_firstname}', $user->get_name(), $content );
				break;


				case '{user_firstname}':

					$content = str_replace('{user_firstname}', $user->get_firstname(), $content );
				break;


				case '{user_lastname}':

					$content = str_replace('{user_name}', $user->get_lastname(), $content );
				break;


				case '{user_email}':

					$content = str_replace('{user_email}', $user->get_email(), $content );
				break;


				case '{url_website}':

					$content = str_replace('{url_website}', site_url(), $content );
				break;


				case '{url_login}':

					$content = str_replace('{url_login}', site_url(), $content );
				break;


				case '{url_ourmenu}':

					$content = str_replace('{url_ourmenu}', site_url(), $content );
				break;


				case '{day_of_week}':

					$content = str_replace('{day_of_week}', site_url(), $content );
				break;


				case '{pickup_location_name}':

					$content = str_replace('{pickup_location_name}', site_url(), $content );
				break;


				case '{pickup_location_datetime}':

					$content = str_replace('{pickup_location_datetime}', site_url(), $content );
				break;


				case '{pickup_location_address}':

					$content = str_replace('{pickup_location_address}', site_url(), $content );
				break;
			endswitch;
		endforeach;

		return $content;
	}
}
add_action('init', ['SS_Email', 'init']);
add_filter('send_email_change_email', '__return_false' );