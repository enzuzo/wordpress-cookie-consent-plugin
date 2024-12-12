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
<textarea name="enzuzo_cookie_consent_prefix_code" rows="10" cols="60" placeholder="<?php esc_attr_e('This code is run before banner for initialization and configuration purposes.', 'enzuzo-cookie-consent'); ?>">
<?php echo esc_html(get_option('enzuzo_cookie_consent_prefix_code')); ?></textarea>
