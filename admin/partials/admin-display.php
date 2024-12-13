<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.enzuzo.com
 * @since      1.0.0
 *
 * @package    Enzuzo_Cookie_Consent
 * @subpackage Enzuzo_Cookie_Consent/admin/partials
 */
?>

<div class="wrap">
    <h2><?php esc_attr_e('Enzuzo Cookie Consent', 'cookie-consent-integration'); ?></h2>
    <p><a href="<?php echo esc_url(admin_url()) ?>/options-general.php?page=enzuzo-cookie-consent&tab=help" class"margin"><?php esc_attr_e('How to use Enzuzo Cookie Consent', 'cookie-consent-integration'); ?></a></p>
	<h2 class="nav-tab-wrapper">
		<?php
	    $tabs = array(
		    'setup' => __('Setup', 'cookie-consent-integration'),
		    'preview' => __('Preview', 'cookie-consent-integration'),
		    'help' => __('Help', 'cookie-consent-integration'),
		    'about' => __('About', 'cookie-consent-integration')
	    );
	    //set current tab
		$nonce = wp_create_nonce('tab_action_nonce');
	    // $tab = ( isset($_GET['tab']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['tab']))) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'setup' );
		$tab = ( isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'setup' );
	    ?>
	    <?php foreach( $tabs as $key => $value ): ?>
			<a class="nav-tab <?php if( $tab == $key ){ echo 'nav-tab-active'; } ?>" href="<?php echo esc_url(admin_url()) ?>options-general.php?page=enzuzo-cookie-consent&tab=<?php echo esc_html($key); ?>"><?php echo esc_html($value); ?></a>
	    <?php endforeach; ?>
	</h2>

	<div class="enzuzo-cookie-consent-tabs">
		<?php if( $tab == 'setup' ): ?>

            <?php flush_rewrite_rules(); ?>
		    <form method="post" action="options.php">
				<?php settings_fields('enzuzo-cookie-consent'); ?>
				<?php do_settings_sections('enzuzo-cookie-consent'); ?>
				<?php submit_button('Save Changes'); ?>
		    </form>

		<?php elseif( $tab == 'preview' ): ?>

            <?php $this->admin_enqueue_scripts(); ?>
			<?php include plugin_dir_path( dirname( __FILE__ ) ) . 'partials/preview/preview.php'; ?>

		<?php elseif( $tab == 'help' ): ?>

			<?php include plugin_dir_path( dirname( __FILE__ ) ) . 'partials/help/help.php'; ?>

		<?php else: ?>

			<?php include plugin_dir_path( dirname( __FILE__ ) ) . 'partials/about/about.php'; ?>

	   <?php endif; ?>
	</div>
</div>
