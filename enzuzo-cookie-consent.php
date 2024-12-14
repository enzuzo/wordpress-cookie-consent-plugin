<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.enzuzo.com
 * @package           Enzuzo_Cookie_Consent
 *
 * @wordpress-plugin
 * Plugin Name:       Enzuzo Cookie Consent | GDPR/CCPA compatible cookie banner
 * Plugin URI:        https://www.enzuzo.com/consent-management-software
 * Description:       Enzuzo Cookie Consent is a cookie consent management that builds trust and keeps you compliant.
 * Version:           1.0.2
 * Author:            Enzuzo Inc.
 * Author URI:        http://www.enzuzo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cookie-consent-integration
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'enzuzo_cookie_consent_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-enzuzo-cookie-consent-activator.php
 */
function activate_enzuzo_cookie_consent() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enzuzo-cookie-consent-activator.php';
	enzuzo_cookie_consent_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-enzuzo-cookie-consent-deactivator.php
 */
function deactivate_enzuzo_cookie_consent() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enzuzo-cookie-consent-deactivator.php';
	enzuzo_cookie_consent_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_enzuzo_cookie_consent' );
register_deactivation_hook( __FILE__, 'deactivate_enzuzo_cookie_consent' );

function enzuzo_cookie_consent_settings_links( $links ) {
    $url = menu_page_url('enzuzo-cookie-consent', false);
    $link = "<a href='$url'>" . __( 'Settings', 'cookie-consent-integration' ) . '</a>';

    array_unshift(
        $links,
        $link
    );

    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'enzuzo_cookie_consent_settings_links' );

function enzuzo_cookie_consent_enabled() {
    return get_option('enzuzo_cookie_consent_enabled', 'true') == 'true';
}

function enzuzo_cookie_consent_get_uuid() {
    $uuid = get_option('enzuzo_cookie_consent_uuid', '');
    if (preg_match('/([0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12})/', $uuid, $matches)) {
        return $matches[0];
    }

    return '';
}

function enzuzo_cookie_consent_enqueue_scripts() {
    $uuid = enzuzo_cookie_consent_get_uuid();
    $script_url = 'https://app.enzuzo.com/scripts/cookiebar';
    if ($uuid) {
        $script_url .= '/' . $uuid;
    }
    wp_enqueue_script(
        'enzuzo_cookie_consent',
        $script_url,
        array(),
        enzuzo_cookie_consent_VERSION,
        array()
    );

    $prefix_code = get_option('enzuzo_cookie_consent_prefix_code');
    if ($prefix_code) {
        wp_add_inline_script(
            'enzuzo_cookie_consent',
            $prefix_code,
            'before'
        );
    }

    function enzuzo_cookie_consent_add_attributes($tag, $handle) {
        $scripts = array('enzuzo_cookie_consent');
        $attributes = array();

        foreach($scripts as $script) {
            if ($script === $handle) {
                $auto_blocking = get_option('enzuzo_cookie_consent_auto_blocking');
                if ($auto_blocking) {
                    $attributes[] = "auto-blocking=\"$auto_blocking\"";
                }

                if (count($attributes) > 0) {
                    $attributes_str = ' ' . implode(' ', $attributes);
                } else {
                    $attributes_str = '';
                }

                return str_replace(' src', $attributes_str . ' src', $tag);
            }
        }

        return $tag;
    }

    add_filter( 'script_loader_tag', 'enzuzo_cookie_consent_add_attributes', 10, 2 );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-enzuzo-cookie-consent.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_enzuzo_cookie_consent() {
	$plugin = new enzuzo_cookie_consent();
    $plugin->run();
}
run_enzuzo_cookie_consent();
