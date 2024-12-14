<div class="clearfix">
    <div class="enzuzo-cookie-consent-right-col">
        <h3><p><?php enzuzo_cookie_consent_enabled() ? esc_attr_e('The banner is enabled', 'enzuzo-cookie-consent') : esc_attr_e('The banner is disabled', 'enzuzo-cookie-consent'); ?></p></h3>
        <h3><p><?php enzuzo_cookie_consent_get_uuid() ? esc_attr_e('The banner has a valid UUID', 'enzuzo-cookie-consent') : esc_attr_e('The banner does not have a valid UUID', 'enzuzo-cookie-consent'); ?></p></h3>
        <p><?php esc_attr_e('Note that there may be differences in the final output due to configuration, CSS, etc.', 'enzuzo-cookie-consent'); ?></p>
	</div>
</div>
