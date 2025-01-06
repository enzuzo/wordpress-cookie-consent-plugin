<?php

/**
 * This file is used to display the checkbox for enabling WP Consent API integration
 *
 * @link       https://www.enzuzo.com
 * @package    Enzuzo_Cookie_Consent
 * @subpackage Enzuzo_Cookie_Consent/admin/partials
 */

$enabled = get_option( 'enzuzo_cookie_consent_enable_wp_consent' );
?>

<input
    type="checkbox"
    id="enzuzo_cookie_consent_enable_wp_consent"
    name="enzuzo_cookie_consent_enable_wp_consent"
    value="1"
    <?php if ( '1' === $enabled ) echo 'checked'; ?>
/>
<label for="enzuzo_cookie_consent_enable_wp_consent">
    <?php esc_html_e( 'Enable integration with the WP Consent API', 'enzuzo-cookie-consent' ); ?>
</label>