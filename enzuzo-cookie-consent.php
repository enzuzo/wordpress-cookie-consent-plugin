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
 * Plugin Name:       Enzuzo Cookie Consent
 * Plugin URI:        https://www.enzuzo.com/consent-management-software
 * Description:       Enzuzo Cookie Consent is a cookie consent management that builds trust and keeps you compliant.
 * Version:           1.1.1
 * Author:            Enzuzo Inc.
 * Author URI:        http://www.enzuzo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       enzuzo-cookie-consent
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit('ABSPATH not defined');
}

define( 'ENZUZO_PLUGIN_VERSION', '1.1.1' );

add_filter('wp_consent_api_registered_' . plugin_basename( __FILE__ ), '__return_true');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-enzuzo-cookie-consent-activator.php
 */
function enzuzo_cookie_consent_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enzuzo-cookie-consent-activator.php';
	enzuzo_cookie_consent_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-enzuzo-cookie-consent-deactivator.php
 */
function enzuzo_cookie_consent_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enzuzo-cookie-consent-deactivator.php';
	enzuzo_cookie_consent_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'enzuzo_cookie_consent_activate' );
register_deactivation_hook( __FILE__, 'enzuzo_cookie_consent_deactivate' );

function enzuzo_cookie_consent_settings_links( $links ) {
    $url = menu_page_url('enzuzo-cookie-consent', false);
    $link = "<a href='$url'>" . __( 'Settings', 'enzuzo-cookie-consent' ) . '</a>';

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

/**
 * Declare the consent type to the WP Consent API. Registered at plugin load,
 * not at wp_enqueue_scripts: with no consent type declared, wp_has_consent()
 * answers "allowed" for everything, so plugins that set cookies server side
 * (at init, before headers are sent) and admin-ajax/REST/cron requests would
 * see consent that was never given.
 */
function enzuzo_cookie_consent_consent_type( $consent_type ) {
    if ( get_option( 'enzuzo_cookie_consent_enable_wp_consent' ) && enzuzo_cookie_consent_enabled() ) {
        return 'optin';
    }

    return $consent_type;
}
add_filter( 'wp_get_consent_type', 'enzuzo_cookie_consent_consent_type' );

function enzuzo_passthrough_sanitize($input) {
    return $input; // do nothing, just return it
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
        ENZUZO_PLUGIN_VERSION,
        array()
    );

    $prefix_code = get_option('enzuzo_cookie_consent_prefix_code');
    if ($prefix_code) {
        wp_add_inline_script('enzuzo_cookie_consent', $prefix_code, 'before');
    }

    if (get_option('enzuzo_cookie_consent_enable_wp_consent')) {
        if ( class_exists( 'WP_CONSENT_API' ) ) {
            $enzuzo_wp_consent_callback = '
                function onSetConsent({ analytics, functional, marketing, preferences }) {
                    const consentMap = {
                        functional,
                        marketing,
                        preferences,
                        statistics: analytics,
                        "statistics-anonymous": analytics,
                    };
                    Object.entries(consentMap).forEach(([category, value]) => {
                        wp_set_consent(category, value ? "allow" : "deny");
                    });
                }
                window.__enzuzoConfig = window.__enzuzoConfig || {};
                window.__enzuzoConfig.callbacks = window.__enzuzoConfig.callbacks || {};
                ["acceptSelected", "acceptAll", "decline"].forEach(callbackName => {
                    const oldCallback = window.__enzuzoConfig.callbacks[callbackName];
                    window.__enzuzoConfig.callbacks[callbackName] = function({ analytics, functional, marketing, preferences }) {
                        onSetConsent({ analytics, functional, marketing, preferences });
                        if (oldCallback) {
                            oldCallback({ analytics, functional, marketing, preferences });
                        }
                    }
                });
                // Copy saved consent into wp_consent_* cookies on every page
                // load. Those cookies expire after 30 days, but Enzuzo keeps
                // consent for 365 days and does not show the banner again, so
                // returning visitors would otherwise count as "deny". Skip if
                // nothing was granted (functional does not count, it is true
                // before any choice) so we never record a choice the visitor
                // did not make.
                const oldInit = window.__enzuzoConfig.callbacks.init;
                window.__enzuzoConfig.callbacks.init = function(consent) {
                    if (consent && (consent.analytics || consent.marketing || consent.preferences)) {
                        const replay = () => onSetConsent(consent);
                        // wp_set_consent loads in the footer and may not exist yet.
                        if (typeof window.wp_set_consent === "function") {
                            replay();
                        } else if (document.readyState === "loading") {
                            document.addEventListener("DOMContentLoaded", replay);
                        }
                    }
                    if (oldInit) {
                        oldInit(consent);
                    }
                };';
            wp_add_inline_script('enzuzo_cookie_consent', $enzuzo_wp_consent_callback, 'before');
        }
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
function enzuzo_cookie_consent_run() {
	$plugin = new enzuzo_cookie_consent();
    $plugin->run();
}
enzuzo_cookie_consent_run();
