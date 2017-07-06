<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget; ?>

<section class="<?= $widget['class']; ?>"<?= NF()->get_bg_image(); ?><?= NF()->get_section_id(); ?>>
	<div class="container">
		<?php the_key('section_title', 'h2', 'h-82'); ?>
		<?= NF()->get_button($widget); ?>
		<?= wpautop(get_key('text')); ?>
	</div>
</section>

<?php $widget = null;