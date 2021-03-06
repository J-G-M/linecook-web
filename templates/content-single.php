<?php if ( ! defined( 'ABSPATH' ) )
	exit;

while (have_posts()) : the_post(); ?>
	<article <?php post_class(); ?>>

		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php get_template_part('templates/partials/entry-meta'); ?>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php comments_template('/templates/partials/comments.php'); ?>
	</article>
<?php endwhile;