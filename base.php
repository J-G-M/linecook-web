<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php get_template_part('templates/partials/head'); ?>
	<body <?php body_class(); ?>>
	<?php
	do_action('get_header');
	get_template_part('templates/partials/header');
	?>
	<div class="wrap">

		<main class="main">
			<?php include Wrapper\template_path(); ?>
		</main>

		<?php if ( current_theme_supports('nf_sidebar') ) : ?>
			<aside class="sidebar">
				<?php get_template_part('templates/partials/sidebar'); ?>
			</aside>
		<?php endif; ?>
	</div>
	<?php
	do_action('get_footer');
	get_template_part('templates/partials/footer');
	wp_footer();
	?>
	</body>
</html>