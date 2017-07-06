<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget; ?>

<section class="<?= $widget['class']; ?>"<?= NF()->get_section_id(); ?>>
	<div class="container">
		<?php the_key('section_title', 'h2', 'section-title'); ?>
		<?= wpautop(get_key('editor')); ?>
	</div>
</section>

<?php $widget = null;