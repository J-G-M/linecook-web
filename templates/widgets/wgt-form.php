<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget; ?>

<section class="<?= $widget['class']; ?>">
	<div class="container">
		<?php the_key('section_title', 'h2', 'section-title'); ?>
		<?php gravity_form( $widget['gform_id'], false,false,false,null, true, 1, true ); ?>
		<?php if (get_key('email')) : ?>
			<a href="mailto:<?= get_key('email'); ?>" class="mailto"><?= get_key('email'); ?></a>
		<?php endif; ?>
	</div>
</section>

<?php $widget = null;