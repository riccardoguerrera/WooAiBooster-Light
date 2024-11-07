<?php

	function woo_ai_booster_light_dl_page() {

		add_submenu_page(
			'woo_ai_booster_light',
			__('Log attività', 'woo-ai-booster'),
			__('Log attività', 'woo-ai-booster'),
			'manage_options',
			'woo_ai_booster_light_debug_log',
			'woo_ai_booster_light_debug_log_page'
		);

	}

	add_action('admin_menu', 'woo_ai_booster_light_dl_page');


	function woo_ai_booster_light_debug_log_page() {

		$log = file_get_contents(WAIBL_PATH . 'waibl-debug.log', true);

		?>

		<h2>Debug Log</h2>

		<div id="woo-ai-booster-light-debug-log"><?php echo $log; ?></div>

		<style>
            #woo-ai-booster-light-debug-log {
				width: 50%;
				height: 400px;
				max-height: 400px;
				background-color: #fff;
				border: 1px solid lightgrey;
				overflow-y: scroll;
				margin-top: 30px;
			}
		</style>

		<?php

	}