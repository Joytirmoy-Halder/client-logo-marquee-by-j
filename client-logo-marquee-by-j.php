<?php
/**
 * Plugin Name:       Client Logo Marquee by J
 * Description:       A sleek, GPU-accelerated client logo marquee for Elementor. Seamless infinite loop, greyscale-to-colour reveal, soft edge fades, pause on hover, pauses itself when off-screen. Fully responsive.
 * Version:           1.0.3
 * Author:            J
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Text Domain:       client-logo-marquee-by-j
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Elementor tested up to: 3.25.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CLMJ_VERSION', '1.0.3' );
define( 'CLMJ_FILE', __FILE__ );
define( 'CLMJ_PATH', plugin_dir_path( __FILE__ ) );
define( 'CLMJ_URL', plugin_dir_url( __FILE__ ) );

final class CLMJ_Plugin {

	const MIN_ELEMENTOR = '3.5.0';
	const MIN_PHP       = '7.4';

	/**
	 * @var CLMJ_Plugin|null
	 */
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_no_elementor' ) );

			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::MIN_ELEMENTOR, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_old_elementor' ) );

			return;
		}

		if ( version_compare( PHP_VERSION, self::MIN_PHP, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_old_php' ) );

			return;
		}

		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Registered, never enqueued directly: the widget declares them through
		// get_style_depends() / get_script_depends() so Elementor only loads them
		// on pages that actually use the widget.
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );

		// The editor renders newly dropped widgets over AJAX, which can miss the
		// dependency-based loading above until the editor is reloaded. Load the
		// assets outright inside the preview iframe so the editor always matches
		// the live page.
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_assets' ) );
	}

	public function register_category( $categories_manager ) {
		$existing = $categories_manager->get_categories();

		if ( isset( $existing['by-j'] ) ) {
			return;
		}

		$categories_manager->add_category(
			'by-j',
			array(
				'title' => esc_html__( 'By J', 'client-logo-marquee-by-j' ),
				'icon'  => 'eicon-globe',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		require_once CLMJ_PATH . 'widgets/class-clmj-widget.php';

		$widgets_manager->register( new CLMJ_Widget() );
	}

	public function register_styles() {
		wp_register_style(
			'clmj-marquee',
			CLMJ_URL . 'assets/css/client-logo-marquee-by-j.css',
			array(),
			CLMJ_VERSION
		);
	}

	public function register_scripts() {
		wp_register_script(
			'clmj-marquee',
			CLMJ_URL . 'assets/js/client-logo-marquee-by-j.js',
			array(),
			CLMJ_VERSION,
			true
		);
	}

	public function enqueue_preview_assets() {
		// Registration order inside the preview is not guaranteed, so make sure
		// the handles exist before asking for them.
		if ( ! wp_style_is( 'clmj-marquee', 'registered' ) ) {
			$this->register_styles();
		}

		if ( ! wp_script_is( 'clmj-marquee', 'registered' ) ) {
			$this->register_scripts();
		}

		wp_enqueue_style( 'clmj-marquee' );
		wp_enqueue_script( 'clmj-marquee' );
	}

	private function notice( $message ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			wp_kses_post( $message )
		);
	}

	public function notice_no_elementor() {
		$this->notice(
			sprintf(
				/* translators: 1: plugin name, 2: Elementor */
				esc_html__( '%1$s requires %2$s to be installed and activated.', 'client-logo-marquee-by-j' ),
				'<strong>' . esc_html__( 'Client Logo Marquee by J', 'client-logo-marquee-by-j' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'client-logo-marquee-by-j' ) . '</strong>'
			)
		);
	}

	public function notice_old_elementor() {
		$this->notice(
			sprintf(
				/* translators: 1: plugin name, 2: Elementor, 3: required version */
				esc_html__( '%1$s requires %2$s version %3$s or greater.', 'client-logo-marquee-by-j' ),
				'<strong>' . esc_html__( 'Client Logo Marquee by J', 'client-logo-marquee-by-j' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'client-logo-marquee-by-j' ) . '</strong>',
				self::MIN_ELEMENTOR
			)
		);
	}

	public function notice_old_php() {
		$this->notice(
			sprintf(
				/* translators: 1: plugin name, 2: PHP, 3: required version */
				esc_html__( '%1$s requires %2$s version %3$s or greater.', 'client-logo-marquee-by-j' ),
				'<strong>' . esc_html__( 'Client Logo Marquee by J', 'client-logo-marquee-by-j' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'client-logo-marquee-by-j' ) . '</strong>',
				self::MIN_PHP
			)
		);
	}
}

CLMJ_Plugin::instance();
