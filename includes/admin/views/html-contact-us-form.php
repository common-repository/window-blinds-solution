<?php
/**
 * Contact us form HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="woocommerce-progress-form-wrapper bmfm-welcome-form-wrapper form header bmfm-freemium-contact-us-form-wrapper bmfm-progress-form-wrapper">
       <form class="wc-progress-form-content woocommerce-importer bmfm-freemium-contact-us-form" enctype="multipart/form-data" method="post">
	   	  <header class="bmfm-progress-form-header">
			<h3><b>Fill the form to get the activation key</b></h3>
		  </header>
		  <section class="bmfm-freemium-contact-us-section">
			  <div class="bmfm-freemium-contact-us-wrapper">
			      <div class="bmfm-form-name">
			          <label><b>Name </b><span class="required">*</span></label>
			          <input type="text" name="bmfm_contact_us_data[name]" required placeholder="Enter your name">
			      </div>
			      
			      <div class="bmfm-form-company-name">
			          <label><b>Company Name</b><span class="required">*</span></label>
			          <input type="text"  name="bmfm_contact_us_data[company_name]" required placeholder="Enter your company name">
			      </div>
			      
			      <div class="bmfm-form-email">
			          <label><b>Email</b><span class="required">*</span></label>
			          <input type="email" name="bmfm_contact_us_data[email]" required placeholder="Enter your email">
			      </div>
			      
			      <div class="bmfm-form-phone-number">
			          <label><b>Phone Number</b><span class="required">*</span></label>
			          <input type="tel" name="bmfm_contact_us_data[tel]" required placeholder="Enter your phone number">
			      </div>
			      
			      <div class="bmfm-form-state">
			          <label><b>Site URL</b><span class="required">*</span></label>
			          <input type="url" name="bmfm_contact_us_data[site_url]" required placeholder="Enter your site URL">
			      </div>
			      
			      <div class="bmfm-form-country">
			          <label><b>Country</b></label>
			          <input type="text" name="bmfm_contact_us_data[country]" placeholder="Enter your country">
			      </div>
			 </div>
			 
			 <div class="bmfm-freemium-contact-us-submit-wrapper">
			     <div class="bmfm-freemium-contact-us-submit">
			          <label></label>
			          <button type="submit" class="bmfm-freemium-contact-us-submit-action button-secondary">Submit</button>
			     </div>
			 </div>
		  </section>
		  <section></section>
		</form>
    </div>