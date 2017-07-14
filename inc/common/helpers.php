<?php

/**
 * Get paged value
 */
if ( ! function_exists('get_page_num')) :

	function get_page_num() {

		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} else if ( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}

		return $paged;
	}
endif;




/**
 * Get value from array key
 */
if ( ! function_exists('get_key')) :

	function get_key($k, $a = false) {

		global $widget, $nf;
		$val = false;


		/**
		 * Falback for old way of getting things
		 */
		if ( is_array($k) || is_object($k) ) :

			$key   = $a;
			$array = $k;
		else:

			$key   = $k;
			$array = $a;
		endif;


		/**
		 * Get value from nf/widget variable
		 */
		if ( ! $array ) :

			if ( is_array($widget) ) {
				$array = $widget;
			}
			elseif ( is_array($nf)) {
				$array = $nf;
			}
		endif;


		if (!is_array($array) && !is_object($array))
			return false;

		if (is_array($array) && array_key_exists($key, $array))
			$val = $array[$key];

		if (is_object($array) && property_exists($array, $key))
			$val = $array->$key;

		return $val;
	}
endif;




/**
 * Get value from array/objec property and echo along with tag and class
 */
if ( ! function_exists('the_key') ) :

	function the_key( $key, $tag = false, $class = false, $array = false ) {


		if ( is_array($array) ) {
			$val = get_key($key, $array);
		}
		else {
			$val = get_key($key);
		}

		if ( ! $val )
			return;

		$open = '<' . $tag;

		if ( $class ) {
			$open .= ' class="'. $class .'"';
		}

		$open .= '>' . $val;
		$open .=  '</' . $tag . '>';

		echo $open;
	}
endif;


/**
 * Strip all but numbers
 */
if ( ! function_exists('get_tel')) :

	function get_tel( $tel ) {
		return preg_replace("/[^0-9]/","",$tel);
	}
endif;



/**
 * Trim text to X words
 */
if ( ! function_exists('vb_trim_word')) :

	function vb_trim_word( $text, $length ) {
		$trimmed = wp_trim_words( $text, $num_words = $length, $more = null );
		return $trimmed;
	}
endif;


/**
 * Trim text to X chars
 */
if ( ! function_exists('vb_trim_chars')) :

	function vb_trim_chars( $text, $length = 45, $append = '&hellip;' ) {

		$length = (int) $length;
		$text   = trim( strip_tags( $text ) );

		if ( strlen( $text ) > $length ) {
			$text  = substr( $text, 0, $length + 1 );
			$words = preg_split( "/[\s]|&nbsp;/", $text, -1, PREG_SPLIT_NO_EMPTY );
			preg_match( "/[\s]|&nbsp;/", $text, $lastchar, 0, $length );

			if ( empty( $lastchar ) )
				array_pop( $words );

			$text = implode( ' ', $words ) . $append;
		}

		return $text;
	}
endif;


/**
 * Debug
 */
if ( ! function_exists('debug_wpmail')) :

	function debug_wpmail( $re ) {

		if ( ! $re ) {

			global $ts_mail_errors;
			global $phpmailer;

			if ( ! isset($ts_mail_errors) )
				$ts_mail_errors = array();

			if ( isset($phpmailer) ) {
				$ts_mail_errors[] = $phpmailer->ErrorInfo;
			}

			print_r('<pre>');
			print_r($ts_mail_errors);
			print_r('</pre>');
		}
	}

endif;


if ( ! function_exists('printaj')) :

	function printaj( $var ) {
		print_r('<pre>');
		print_r($var);
		print_r('</pre>');
	}

endif;


if ( ! function_exists('dumpaj')) :

	function dumpaj( $var ) {
		var_dump('<pre>');
		var_dump($var);
		var_dump('</pre>');
	}
endif;