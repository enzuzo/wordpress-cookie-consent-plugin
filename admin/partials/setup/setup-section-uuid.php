<?php

/**
 * This file is used to setup a settings section
 *
 * @link       https://www.enzuzo.com
 * @package    Enzuzo_Cookie_Consent
 * @subpackage Enzuzo_Cookie_Consent/admin/partials
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit('ABSPATH not defined');
}
?>
<textarea name="enzuzo_cookie_consent_uuid" rows="4" cols="60" placeholder="<?php esc_html_e('Example: ', 'enzuzo-cookie-consent'); ?>
Installation code snippet or UUID (looks like aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee) from Enzuzo Admin Dashboard">
<?php
    $option = get_option('enzuzo_cookie_consent_uuid'); 
    $uuid = enzuzo_cookie_consent_get_uuid();

    if ($uuid) {
        echo esc_html($uuid);
    } else {
        echo esc_html($option);
    }
?>
</textarea>
