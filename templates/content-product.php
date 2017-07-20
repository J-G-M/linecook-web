<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $product;
$overlay = get_post_meta(get_the_ID(), 'overlay', true); ?>

<article class="product-loop">

	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="thumb">
			<?php if ( $product->get_stock_status() != 'instock') : ?>
				<span class="stock-status">Sold Out</span>
			<?php endif;

			if ( $overlay ) : ?>
				<span class="product-overlay"><?= $overlay; ?></span>
			<?php endif;

			the_post_thumbnail( 'shop_single' ); ?>
		</a>
	<?php endif; ?>

	<header>
		<h2 class="product-title">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
				<span class="price"><?= $product->get_price_html(); ?></span>
			</a>
		</h2>
	</header>
</article>