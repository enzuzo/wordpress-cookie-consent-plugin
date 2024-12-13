<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.enzuzo.com
 * @since      1.0.1
 *
 * @package    Enzuzo_Cookie_Consent
 * @subpackage Enzuzo_Cookie_Consent/admin
 */
class Enzuzo_Cookie_Consent_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $enzuzo_cookie_consent    The ID of this plugin.
	 */
	private $enzuzo_cookie_consent;

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
	 * @param      string    $enzuzo_cookie_consent       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $enzuzo_cookie_consent, $version ) {

		$this->enzuzo_cookie_consent = $enzuzo_cookie_consent;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function admin_enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Enzuzo_Cookie_Consent_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enzuzo_Cookie_Consent_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->enzuzo_cookie_consent, plugin_dir_url( __FILE__ ) . 'css/enzuzo-cookie-consent-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function admin_enqueue_scripts() {
		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Enzuzo_Cookie_Consent_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enzuzo_Cookie_Consent_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        enzuzo_cookie_consent_enqueue_scripts();
        wp_add_inline_script(
            'enzuzo_cookie_consent',
            '<script>window.__enzuzoConfig = window.__enzuzoConfig ?? {};window.__enzuzoConfig.bannerMode = \'optin\';</script>',
            'before'
        );
    }

    public function settings_menu_init() {
		// The setup section
		add_settings_section(
			'enzuzo_cookie_consent_setup_settings_section',
			__( 'Setup', 'cookie-consent-integration' ),
			array( $this, 'setup_section_callback_function' ),
			'enzuzo-cookie-consent'
        );

        /**
         * setup settings
         */

        // account UUID
        add_settings_field(
            'enzuzo_cookie_consent_uuid',
            '<span class="enzuzo-cookie-consent-tooltip" title="' . __( 'Set your installation code snippet or UUID from Enzuzo Dashboard (do not add extra code here - only the UUID will be used)', 'cookie-consent-integration' ) . '">?</span>' . __( 'installation code snippet or account UUID (required):', 'cookie-consent-integration' ),
            array( $this, 'setup_section_callback_uuid_function' ),
            'enzuzo-cookie-consent',
            'enzuzo_cookie_consent_setup_settings_section'
        );
        register_setting( 'enzuzo-cookie-consent', 'enzuzo_cookie_consent_uuid' );

        // enabled
        add_settings_field(
            'enzuzo_cookie_consent_enabled',
            '<span class="enzuzo-cookie-consent-tooltip" title="' . __( 'Enable banner', 'cookie-consent-integration' ) . '">?</span>' . __( 'enable banner:', 'cookie-consent-integration' ),
            array( $this, 'setup_section_callback_enabled_function' ),
            'enzuzo-cookie-consent',
            'enzuzo_cookie_consent_setup_settings_section'
        );
        register_setting( 'enzuzo-cookie-consent', 'enzuzo_cookie_consent_enabled', array( 'type' => 'string', 'default' => 'true' ) );

        // auto-blocking
        add_settings_field(
            'enzuzo_cookie_consent_auto_blocking',
            '<span class="enzuzo-cookie-consent-tooltip" title="' . __( 'Script auto-blocking settings', 'cookie-consent-integration' ) . '">?</span>' . __( 'auto blocking:', 'cookie-consent-integration' ),
            array( $this, 'setup_section_callback_auto_blocking_function' ),
            'enzuzo-cookie-consent',
            'enzuzo_cookie_consent_setup_settings_section'
        );
        register_setting( 'enzuzo-cookie-consent', 'enzuzo_cookie_consent_auto_blocking' );

        // prefix-script
        add_settings_field(
            'enzuzo_cookie_consent_prefix_code',
            '<span class="enzuzo-cookie-consent-tooltip" title="' . __( 'JavaScript code to run before banner', 'cookie-consent-integration' ) . '">?</span>' . __( 'prefix code:', 'cookie-consent-integration' ),
            array( $this, 'setup_section_callback_prefix_code_function' ),
            'enzuzo-cookie-consent',
            'enzuzo_cookie_consent_setup_settings_section'
        );
        register_setting( 'enzuzo-cookie-consent', 'enzuzo_cookie_consent_prefix_code' );
    }

    /**
     * Callback function for the admin settings page.
     *
     * @since    1.0.1
     */
    public function create_admin_interface() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-display.php';
    }

    /**
	 * Register the settings page
	 *
	 * @since    1.0.1
	 */
	public function add_admin_menu() {
		add_options_page( 'Enzuzo Cookie Consent', 'Enzuzo', 'manage_options', 'enzuzo-cookie-consent', array( $this, 'create_admin_interface' ) );
    }

    /*
     * Setup
     */
    function setup_section_callback_function() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setup/setup-section-display.php';
    }

    function setup_section_callback_uuid_function() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setup/setup-section-uuid.php';
    }

    function setup_section_callback_auto_blocking_function() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setup/setup-section-auto-blocking.php';
    }

    function setup_section_callback_prefix_code_function() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setup/setup-section-prefix-code.php';
    }

    function setup_section_callback_enabled_function() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setup/setup-section-enabled.php';
    }
}
