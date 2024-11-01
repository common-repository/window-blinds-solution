<?php
/**
 * Freemium Days Remaining Information HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$saved_date_timestamp = BMFM_User_Request::get_freemium_activated_date();
if($saved_date_timestamp):
    $expiry_date_timestamp = strtotime('+30 days',$saved_date_timestamp);
    $current_date_obj    = new DateTime();
    $future_date_obj     = new DateTime(gmdate('Y-m-d H:i:s',$expiry_date_timestamp));
    $remaining_date_obj  = $current_date_obj->diff($future_date_obj);
    ?>
    <div class="bmfm-freemium-days-remaining-msg"><?php echo intval($remaining_date_obj->days)  + 1; ?> days remaining.</div>
<?php endif; ?>
