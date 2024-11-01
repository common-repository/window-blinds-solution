<?php
/**
 * Upgrade Premium Information HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="woocommerce-progress-form-wrapper bmfm-premium-wrapper bmfm-progress-form-wrapper">
	<header class="bmfm-progress-header bmfm-premium-header">
			<h3><b><span>The free version allows you to add upto two products with up to 50 fabrics.</span> <span>To add more, upgrade to full version.</span></b></h3>
	</header>
	<section class="bmfm-empty-section"></section>
	<section class="bmfm-premium-section">
		<?php 
		$premium_info_data1 = array('Add unlimited products','World class security');
		$premium_info_data2 = array('Free samples','24/5 Technical support');
		?>
		<div class="bmfm-premium-info-wrapper">		
			<div class="bmfm-premium-info-lists">	
				<?php
				foreach ($premium_info_data1 as $label) :
					?>
				<div class="bmfm-premium-info">
					<span class="dashicons dashicons-saved bmfm-premium-info-tick-icon"></span><?php echo wp_kses_post($label); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="bmfm-premium-info-lists">	
				<?php
				foreach ($premium_info_data2 as $label) :
					?>
				<div class="bmfm-premium-info">
					<span class="dashicons dashicons-saved bmfm-premium-info-tick-icon"></span><?php echo wp_kses_post($label); ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<section class="bmfm-empty-section"></section>
		<div class="bmfm-premium-info-buttons-wrapper">
			<a href="https://blindmatrix.com/pricing/" target="_blank" class="button-secondary bmfm-premium-info-button bmfm-buy-now-button">Buy now<span class="dashicons dashicons-cart"></span></a>
			<a href="https://blindmatrix.com/contact-us/" target="_blank" class="button-secondary bmfm-premium-info-button bmfm-speak-to-consultant-button">Get help<img class="bmfm-speak-to-consultant-dashicon" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/headphone.png"></a>
			<a href="https://ecommerce.blindssoftware.com/" target="_blank" class="button-secondary bmfm-premium-info-button bmfm-view-demo-site-button" >View demo site<img class="bmfm-view-demo-site-dashicon" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/crown.png"></a>
			<div class="bmfm-blindmatrix-site-link-wrapper">
				<span>Powered by</span> <a href="https://blindmatrix.com/" target="_blank">BlindMatrix</a>
			</div>
		</div>
	</section>
	<section class="bmfm-empty-section"></section>
	<section class="bmfm-empty-section"></section>
</div>
