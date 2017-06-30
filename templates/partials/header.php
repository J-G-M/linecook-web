<header class="header">
	<div class="container">
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

		if ( has_nav_menu('nav_main_left') ) : ?>
			<nav class="navbar-main left">
				<?php wp_nav_menu(['theme_location' => 'nav_main_left', 'menu_class' => 'nav nav-main left', 'container' => false] ); ?>
			</nav>
		<?php endif; ?>

		<div class="logo">
			<?php NF()->get_logo(); ?>
		</div>

		<?php if ( has_nav_menu('nav_main_right') ) : ?>
			<nav class="navbar-main right">
				<?php wp_nav_menu(['theme_location' => 'nav_main_left', 'menu_class' => 'nav nav-main right', 'container' => false] ); ?>
			</nav>
		<?php endif; ?>
	</div>
</header>