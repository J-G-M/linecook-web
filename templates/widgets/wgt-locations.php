<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget; ?>

<section class="<?= $widget['class']; ?>"<?= NF()->get_section_id(); ?>>
	<div class="container">
		<div id="map" data-type="<?= get_key('wgt_type'); ?>" data-zoom="<?= get_key('zoom_level'); ?>"></div>
	</div>
</section>

<?php $widget = null;