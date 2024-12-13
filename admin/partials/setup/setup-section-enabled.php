<?php

/**
 * This file is used to setup a settings section
 *
 * @link       https://www.enzuzo.com
 * @since      1.0.1
 *
 * @package    Enzuzo_Cookie_Consent
 * @subpackage Enzuzo_Cookie_Consent/admin/partials
 */
?>
<?php $enabled = get_option('enzuzo_cookie_consent_enabled'); ?>

<input
    type="checkbox"
    name="enzuzo_cookie_consent_enabled"
    value="true" 
    <?php if ($enabled == "true") echo "checked" ?>
/>
