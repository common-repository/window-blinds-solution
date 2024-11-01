<?php
/**
 * Contact Us Email HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p>Hi,</p>
<?php if($header_message): ?>
    <p><?php echo wp_kses_post($header_message); ?></p>
<?php endif; ?>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" >

	<tbody>
		<tr>
		   <th class="td" scope="col" class="name" style="text-align:left;">Name</th>
		   <td scope="col" style="color:#636363;border:1px solid #e5e5e5;padding:12px"><?php echo wp_kses_post(!empty($name) ? $name:'-'); ?></td>
		</tr>
		
		<tr>
		   <th class="td" scope="col" class="company-name" style="text-align:left;">Company Name</th> 
		   <td scope="col" style="color:#636363;border:1px solid #e5e5e5;padding:12px"><?php echo wp_kses_post(!empty($company_name) ? $company_name:'-'); ?></td>
		</tr>
		
		<tr>
		   <th class="td" scope="col" class="email" style="text-align:left;">Email</th> 
		   <td scope="col" style="color:#636363;border:1px solid #e5e5e5;padding:12px"><?php echo wp_kses_post(!empty($email) ? $email:'-'); ?></td>
		</tr>   
		
		<tr>   
		   <th class="td" scope="col" class="phone-number" style="text-align:left;">Phone Number</th>
		   <td scope="col" style="color:#636363;border:1px solid #e5e5e5;padding:12px"><?php echo wp_kses_post(!empty($tel) ? $tel:'-'); ?></td>
		</tr>   
		
		<tr>   
		   <th class="td" scope="col" class="site_url" style="text-align:left;">Site URL</th>
		   <td scope="col" style="color:#636363;border:1px solid #e5e5e5;padding:12px"><?php echo wp_kses_post(!empty($site_url) ? $site_url:'-'); ?></td>
		</tr>      
		
		<tr>   
		    <th class="td" scope="col" class="country" style="text-align:left;">Country</th>
		    <td scope="col" style="color:#636363;border:1px solid #e5e5e5;padding:12px"><?php echo wp_kses_post(!empty($country) ? $country:'-'); ?></td>
		</tr>
	</tbody>
</table>
<br>
<p>Thanks.</p>
