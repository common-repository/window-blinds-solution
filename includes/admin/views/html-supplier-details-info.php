<?php
/**
 * Supplier details information HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bmfm-supplier-details-wrapper">
	<div style="margin-bottom:10px;">Hi,</div>
	<table class="td" cellspacing="0" cellpadding="6" style="width: 40%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<tr>
			<th style="text-align:left;">Supplier Name</th>
			<td><?php echo wp_kses_post(!empty($api_array['supplier_name']) ? $api_array['supplier_name']:'-'); ?></td>
		</tr>
			
		<tr>
			<th style="text-align:left;">Company Name</th>
			<td><?php echo wp_kses_post(!empty($api_array['company_name']) ? $api_array['company_name']:'-'); ?></td>
		</tr>
			
		<tr>
			<th style="text-align:left;">Account Manager</th>
			<td><?php echo wp_kses_post(!empty($api_array['acc_manager']) ? $api_array['acc_manager']:'-'); ?></td>
		</tr>
			
		<tr>
			<th style="text-align:left;">Name</th>
			<td><?php echo wp_kses_post(!empty($api_array['name']) ? $api_array['name']:'-'); ?></td>
		</tr>
			
		<tr>
			<th style="text-align:left;">Account Manager Email</th>
			<td><?php echo wp_kses_post(!empty($api_array['acc_manager_email']) ? $api_array['acc_manager_email']:'-'); ?></td>
		</tr>

		<tr>
			<th style="text-align:left;">Email</th>
			<td><?php echo wp_kses_post(!empty($api_array['email']) ? $api_array['email']:'-'); ?></td>
		</tr>

		<tr>
			<th style="text-align:left;">Account Manager Phone</th>
			<td><?php echo wp_kses_post(!empty($api_array['acc_manager_ph']) ? $api_array['acc_manager_ph']:'-'); ?></td>
		</tr>

		<tr>
			<th style="text-align:left;">Phone</th>
			<td><?php echo wp_kses_post(!empty($api_array['ph_no']) ? $api_array['ph_no']:'-'); ?></td>
		</tr>
	</table>
	<div style="margin-top:10px;">Thanks.</div>
</div>

