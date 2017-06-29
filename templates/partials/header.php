<header class="banner">
  
	<button type="button" class="navbar-toggle" data-toggle="offcanvas">
    	<span>Toggle navigation</span>
	</button>

	<?php if ( has_nav_menu('primary_navigation') ) : ?>
	    <nav class="navbar-main">
	    	<?php wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-main', 'container' => false] ); ?>
	    </nav>
	<?php endif; ?>
</header>