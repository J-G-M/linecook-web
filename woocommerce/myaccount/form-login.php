<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$register = isset($_GET['register']) ? true : false;
$acc = get_option('woocommerce_myaccount_page_id');


wc_print_notices();

do_action( 'woocommerce_before_customer_login_form' );


if ( ! $register ) : ?>

	<form class="woocomerce-form woocommerce-form-login login" method="post">
		<h2><?php _e( 'Sign In', 'woocommerce' ); ?></h2>

		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<p class="login-lead">Please create a Linecook account to finish your food pickup. Thanks!</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="username"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" />
		</p>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<p class="form-row">
			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<input type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Sign In', 'woocommerce' ); ?>" />
			<?php /*label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
				<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php _e( 'Remember me', 'woocommerce' ); ?></span>
			</label */ ?>
		</p>
		<?php /* p class="woocommerce-LostPassword lost_password">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
		</p */ ?>

		<?php do_action( 'woocommerce_login_form_end' ); ?>

		<span class="fb-or">or</span>
		<?= do_shortcode('[fbl_login_button redirect="" hide_if_logged="true"]' ); ?>

		<p class="register">
			Don’t have an account? <a href="<?= add_query_arg('register', 'true', get_permalink($acc)); ?>">Register now!</a>
		</p>

	</form>

<?php else : ?>

	<form method="post" class="register">

		<h2><?php _e( 'Register', 'woocommerce' ); ?></h2>

		<?php do_action( 'woocommerce_register_form_start' ); ?>

		<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
			</p>

		<?php endif; ?>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>" />
		</p>

		<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" />
			</p>

		<?php endif; ?>

		<!-- Spam Trap -->
		<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" autocomplete="off" /></div>

		<?php do_action( 'woocommerce_register_form' ); ?>

		<p class="woocomerce-FormRow form-row">
			<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
			<input type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" />
		</p>

		<span class="fb-or">or</span>
		<?= do_shortcode('[fbl_login_button redirect="" hide_if_logged="true"]' ); ?>

		<?php do_action( 'woocommerce_register_form_end' ); ?>

	</form>
<?php endif;

do_action( 'woocommerce_after_customer_login_form' );