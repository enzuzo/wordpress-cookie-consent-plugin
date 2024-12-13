<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Enzuzo_Cookie_Consent
 * @subpackage Enzuzo_Cookie_Consent/public
 */
class Enzuzo_Cookie_Consent_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $Enzuzo_Cookie_Consent    The ID of this plugin.
	 */
	private $Enzuzo_Cookie_Consent;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $Enzuzo_Cookie_Consent       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Enzuzo_Cookie_Consent, $version ) {

		$this->Enzuzo_Cookie_Consent = $Enzuzo_Cookie_Consent;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.1
	 */
	public function public_enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Enzuzo_Cookie_Consent_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enzuzo_Cookie_Consent_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

         // wp_enqueue_style( $this->Enzuzo_Cookie_Consent, plugin_dir_url( __FILE__ ) . 'css/enzuzo-cookie-consent-public.css', array(), $this->version, 'all' );
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.1
	 */
	public function public_enqueue_scripts() {
		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Enzuzo_Cookie_Consent_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enzuzo_Cookie_Consent_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
         */

        if (enzuzo_cookie_consent_enabled()) {
            enzuzo_cookie_consent_enqueue_scripts();
        }
	}
}
