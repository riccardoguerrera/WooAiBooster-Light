<?php

	/**
	 * Plugin Name: WooCommerce AI Booster Light
	 * Plugin URI: https://wooaibooster.com
	 * Description: An Woocommerce plugin that helps you optimize Woocommerce Tabs with AI support.
	 * Version: 1.0.0
	 * Author: Riccardo Guerrera
	 * Author URI: https://riccardoguerrera.dev
	 * Text Domain: woo-ai-booster
	 * Domain Path: /languages
	 * Requires at least: 6.5
	 * Requires PHP: 7.4
	 *
	 * @package WooCommerce
	 */


    defined('ABSPATH') || exit;


    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }


    define('WAIBL_VERSION', '1.0.0');


    function woo_ai_booster_light_check_woocommerce_dependency() {

        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            add_action('admin_notices', 'woo_ai_booster_light_show_woocommerce_dependency_notice');
            deactivate_plugins(plugin_basename(__FILE__));
        }

    }

    function woo_ai_booster_light_show_woocommerce_dependency_notice() {

    ?>
        <div class="error notice">
            <p><?php _e('Il plugin "Woo AI Booster Light" richiede WooCommerce per funzionare. WooCommerce non è attivo, quindi il plugin è stato disattivato.', 'woo-ai-booster'); ?></p>
        </div>
    <?php

    }

    add_action('admin_init', 'woo_ai_booster_light_check_woocommerce_dependency');


	function woo_ai_booster_light_load_textdomain() {

		load_plugin_textdomain('woo-ai-booster', false, dirname(plugin_basename(__FILE__ )). '/languages/');

	}

	add_action('plugins_loaded', 'woo_ai_booster_light_load_textdomain');


	function woo_ai_booster_light_set_english_language_as_fallback($mofile, $domain) {

		if ($domain !== 'woo-ai-booster') {
			return $mofile;
		}

		$en_mofile = dirname($mofile) . '/' . $domain . '-en_US.mo';

		if (!file_exists($mofile) && file_exists($en_mofile)) {

			return $en_mofile;

		}

		return $mofile;

	}

	add_filter('load_textdomain_mofile', 'woo_ai_booster_light_set_english_language_as_fallback', 10, 2);


	function woo_ai_booster_light_check_and_register_option() {

        $ai = array(
            'chat_gpt'  => 'ChatGPT',
        );

        if (!get_option('waibl_ai')) {
            add_option('waibl_ai', $ai);
        }

    }

    register_activation_hook(__FILE__, 'woo_ai_booster_light_check_and_register_option');


    function woo_ai_booster_light_init_plugin() {

        $installed_version = get_option('waibl_version');

        if (!$installed_version) {
            woo_ai_booster_light_install();
        } elseif (version_compare($installed_version, WAIBL_VERSION, '<')) {
            woo_ai_booster_light_update_plugin($installed_version);
        }

    }

    function woo_ai_booster_light_install() {

        $default_ai = array(
            'chat_gpt'  => 'ChatGPT',
        );

        add_option('waibl_ai', $default_ai);

        add_option('waibl_version', WAIBL_VERSION);

    }

    function woo_ai_booster_light_update_plugin($installed_version) {

        if (version_compare($installed_version, '1.0.0', '<')) {

            $updated_ai = array(
                'chat_gpt'  => 'ChatGPT',
            );

            update_option('waibl_ai', $updated_ai);

        }

        update_option('waibl_light_version', WAIBL_VERSION);

    }

    add_action('init', 'woo_ai_booster_light_init_plugin');


    /**/

	function woo_ai_booster_light_crb_load() {

        define('WAIBL_PATH', plugin_dir_path(__FILE__));
        define('WAIBL_URL', plugin_dir_url(__FILE__));

        require_once('vendor/autoload.php');

        require_once 'helper/log.php';
        require_once 'helper/woocommerce.php';
        require_once 'helper/admin.php';
        require_once 'admin/settings-page.php';
        require_once 'admin/meta-box.php';
        require_once 'admin/debug-log.php';
        require_once 'includes/ai-woo-tabs-content.php';
        require_once 'includes/ai-connection/chat-gpt/connect.php';

	}

	add_action('plugins_loaded', 'woo_ai_booster_light_crb_load');


	function woo_ai_booster_light_script($hook) {

		if ($hook !== 'toplevel_page_woo_ai_booster_light') return;

		wp_enqueue_script('woo-ai-booster-light', plugins_url('assets/js/waibl.js', __FILE__), array('jquery'), '1.0', true);
		wp_enqueue_style('woo-ai-booster-light', plugins_url('assets/css/waibl.css', __FILE__), null, '1.0');

	}

	add_action('admin_enqueue_scripts', 'woo_ai_booster_light_script');