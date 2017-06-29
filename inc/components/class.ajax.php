<?php if ( ! defined( 'ABSPATH' ) )
	exit;

class NF_Ajax {

	public static function init() {
		$class = __CLASS__;
		new $class;
	}



	public function __construct() {

		global $nf, $current_user;

		// Process any form
		add_action('wp_ajax_do_process_form', [$this, 'process_form']);
		add_action('wp_ajax_nopriv_do_process_form', [$this, 'process_form']);

	}



	public function process_form() {

		if ( ! check_ajax_referer( 'nonce', 'nonce' ) )
			wp_die('Verification Failed');

		$data = get_key('data', $_REQUEST);
		$type = get_key('form', $_REQUEST);

		// printaj($data);

		$r = [
			'code'   => 501,
			'errors' => 'Something went wrong, please try again later'
		];

		// Validation failed
		if ( get_key('errors', $data) )
			wp_die(json_encode($data));



		switch ($type) :
			case 'signin':

				$r = $this->user_signin($data);
			break;
		endswitch;


		wp_die(json_encode($r));
	}






	/**
	 * Upload single file
	 */
	public function upload_file( $file ) {

		$r = [
			'code'   => 201,
			'errors' => 'Error uploading file'
		];

		// No file
		if ( ! $file )
			return $r;



		// Wrong type / size
		if ( ! in_array($file['type'], ['image/png', 'image/jpg', 'image/jpeg']) || ($file['size'] / 1000) > 2048 ) :

			$r = [
				'code'   => 202,
				'errors' => 'The uploaded file exceeds the limit'
			];

			return $r;
		endif;



		// Upload single file
		$attach = $this->insert_attachment($file);

		if ( isset($attach['error']) ) :

			$r = [
				'code'   => 203,
				'errors' => 'The uploaded file exceeds the limit'
			];

			return $r;

		endif;



		$r = [
			'code'    => 200,
			'post_id' => $attach
		];

		return $r;
	}








	/**
	 * Upload attachment to WP Media library
	 */
	private function insert_attachment($doc, $attach = false) {

		$file_return = wp_handle_upload($doc, array('test_form' => false ));

		if (isset($file_return['error']) || isset($file_return['upload_error_handler']) ) :

			return $file_return;

		else :

			$filename   = $file_return['file'];
			$attachment = [
				'post_mime_type' => $file_return['type'],
				'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $file_return['url']
		    ];

		    /**
		     * Attach Image to profile post object
		     */
		    if ($attach) :

		    	$attachment_id = wp_insert_attachment($attachment, $filename, $attach);
		    else :

		    	$attachment_id = wp_insert_attachment($attachment, $filename);
		   	endif;

		    $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);

		    wp_update_attachment_metadata($attachment_id, $attachment_data);


		    return $attachment_id;
	  	endif;
	}
}
add_action('init', ['NF_Ajax', 'init']);