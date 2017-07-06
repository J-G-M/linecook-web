<header class="header">
	<div class="container">

		<?php if ( has_nav_menu('nav_main_left') ) : ?>
			<nav class="navbar-main left">
				<?php wp_nav_menu(['theme_location' => 'nav_main_left', 'menu_class' => 'nav nav-main left', 'container' => false] ); ?>
			</nav>
		<?php endif; ?>

		<div class="logo">
			<?php NF()->get_logo(); ?>
		</div>

		<?php if ( has_nav_menu( 'nav_mobile' ) ) : ?>
			<nav class="navbar-mobile">
				<button type="button" class="navbar-toggle" data-toggle="nav-mobile">
					<span>Toggle navigation</span>
				</button>

				<?php wp_nav_menu([
					'theme_location' => 'nav_mobile',
					'menu_class'     => 'nav nav-mobile toggler',
					'menu_id'        => 'nav-mobile'
				]); ?>
			</nav>
		<?php endif;

		if ( has_nav_menu('nav_main_right') ) : ?>
			<nav class="navbar-main right">
				<?php wp_nav_menu(['theme_location' => 'nav_main_right', 'menu_class' => 'nav nav-main right', 'container' => false] ); ?>
			</nav>
		<?php endif; ?>


		<nav class="navbar-user">
			<ul class="nav-user">
				<?php

				$nav_shop = wc_get_account_menu_items();

				if ( is_user_logged_in() ) : ?>
					<li>
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><?php echo esc_html( $nav_shop['dashboard'] ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'customer-logout' ) ); ?>"><?php echo esc_html( $nav_shop['customer-logout'] ); ?></a>
					</li>
				<?php else : ?>
					<li>
						<a href="<?= get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" data-modal="modal-login">Log In</a>
					</li>
				<?php endif;

				if ( ! is_cart() && ! is_checkout() ) :
					if ( ! WC()->cart->is_empty() ) : ?>
						<li>
							<a href="<?= WC()->cart->get_cart_url(); ?>">
								Cart (<?= sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?>)
							</a>
						</li>
					<?php endif;
				endif;?>
			</ul>
		</nav>
	</div>
</header>