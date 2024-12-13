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
<?php $autoblocking = get_option('enzuzo_cookie_consent_auto_blocking'); ?>

<select name="enzuzo_cookie_consent_auto_blocking" id="enzuzo_cookie_consent_auto_blocking">
    <option value="" <?php if ($autoblocking != "true") echo "selected" ?>>allow (default)</option>
    <option value="true" <?php if ($autoblocking == "true") echo "selected" ?>>true</option>
</select>
