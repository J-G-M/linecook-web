<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget;

?>

<section class="<?= $widget['class']; ?>"<?= NF()->get_bg_image(); ?><?= NF()->get_section_id(); ?>>
	<div class="container">
		<?php the_key('title', 'h1', 'h-82 main-title'); ?>
		<?= NF()->get_button($widget['button']); ?>
		<?php the_key('subtitle', 'h2', 'h-82 sub-title');

		the_key('lead', 'h5', 'lead');

		$meals = get_key('meals');

		if ($meals) : ?>
			<ul class="meals">
				<?php foreach ($meals as $meal) : ?>
					<li>
						<?= NF()->get_acf_image($meal['image'], 'medium'); ?>
						<?php the_key('title', 'h2', 'h-18', $meal); ?>
						<?= wpautop($meal['text']); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>

<?php $widget = null;