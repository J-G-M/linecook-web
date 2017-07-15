<?php if ( ! defined( 'WPINC' ) ) die;

/**
 * Cron Jobs Functions
 */
class NF_Cron {


	/**
	 * Notify users about pickup 24 hours before
	 */
	public static function schedule_notify_order_pickup() {

		if ( ! wp_next_scheduled ( 'notify_order_pickup' ) )
			wp_schedule_event(time(), 'hourly', 'notify_order_pickup');
	}


	public static function do_notify_order_pickup() {

		global $zone;

		$date = new DateTime('now', $zone);
		$args = [
			'post_type'        => 'shop_order',
			'posts_per_page'   => -1,
			'suppress_filters' => true,
			'post_status'      => 'wc-completed',
			'meta_query'       => [
				'relation' => 'AND',
				[
					'key'     => 'pickup_day_time',
					'value'   => [ $date->modify('+1 day')->format('U'), $date->modify('+1 day')->format('U') ],
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC'
				],
				[
					'key'     => '_notify_pickup',
					'compare' => 'NOT EXISTS'
				],
			]
		];

		$qry  = new WP_Query($args);

		if ( $qry->have_posts() ) : while ( $qry->have_posts() ) : $qry->the_post();

			$post_id = get_the_ID();
			$order   = wc_get_order($post_id);

			NF_Email::CustomerNotify(1, $post_id);

			update_post_meta( $post_id, '_notify_pickup', time() );

			sleep(5);

		endwhile; endif;
	}














	/**
	 * Send email 24 hours after order pickup to ask for a feedback
	 */
	public static function schedule_notify_order_feedback() {

		if ( ! wp_next_scheduled ( 'notify_order_feedback' ) )
			wp_schedule_event(time(), 'hourly', 'notify_order_feedback');
	}


	public static function do_notify_order_feedback() {

		global $zone;

		$date = new DateTime('now', $zone);

		if ( $date->format('H') < 15 )
			return;

		$start = $date->modify('-1 day')->format('Y-m-d') . ' 00:01';
		$end   = date('Y-m-d H:i', strtotime($date->format('Y-m-d') . ' 23:59'));

		$args = [
			'post_type'        => 'shop_order',
			'posts_per_page'   => -1,
			'suppress_filters' => true,
			'post_status'      => 'wc-completed',
			'meta_query'       => [
				'relation' => 'AND',
				[
					'key'     => 'pickup_day_time',
					'value'   => [ strtotime($start), strtotime($end) ],
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC'
				],
				[
					'key'     => '_notify_feedback',
					'compare' => 'NOT EXISTS'
				],
			]
		];


		$qry = new WP_Query($args);

		if ( $qry->have_posts() ) : while ( $qry->have_posts() ) : $qry->the_post();

			$post_id = get_the_ID();
			$order   = wc_get_order($post_id);

			NF_Email::CustomerNotify(2, $post_id);
			update_post_meta( $post_id, '_notify_feedback', time() );

			sleep(5);

		endwhile; endif;
	}













	/**
	 * Add schedule
	 */
	public static function add_schedules( $schedules ) {

		$schedules['weekly'] = [
			'interval' => 7 * 24 * 60 * 60,
			'display'  => __( 'Once a Week', 'nof' )
		];

		// Used for testing
		$schedules['fifteen_min'] = [
			'interval' => 60 * 15,
			'display'  => __( '15 Minutes', 'nof' )
		];

		return $schedules;
	}
}
add_filter('cron_schedules', ['NF_Cron', 'add_schedules']);


/**
 * Schedule Cron Jobs on production website
 */
add_action('init', ['NF_Cron', 'schedule_notify_order_pickup']);
add_action('notify_order_pickup', ['NF_Cron', 'do_notify_order_pickup']);


add_action('init', ['NF_Cron', 'schedule_notify_order_feedback']);
add_action('notify_order_feedback', ['NF_Cron', 'do_notify_order_feedback']);