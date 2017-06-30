<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget; ?>

<section class="<?= $widget['class']; ?>">
	<div class="container">
		<div id="map" data-type="<?= get_key('wgt_type'); ?>"></div>
	</div>
</section>

<?php $widget = null;