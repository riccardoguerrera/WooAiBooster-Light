<?php

	function woo_ai_booster_light_add_metabox() {

		add_meta_box(
			'woo_ai_booster_light_product_tabs',
			'WooAiBooster - Tabs',
			'woo_ai_booster_light_product_tabs_metabox',
			'product',
			'normal'
		);

	}

	add_action('add_meta_boxes', 'woo_ai_booster_light_add_metabox');


	function woo_ai_booster_light_product_tabs_metabox($post) {

		$wc_tabs = woo_ai_booster_light_helper_get_woo_tabs();

		foreach ($wc_tabs as $wc_tab_key => $wc_tab_data) {

			$wc_tab = get_post_meta($post->ID, 'woo_ai_booster_light_' . str_replace('-', '_', $wc_tab_key) . '_tab', true);

	?>

		<div style="margin: 10px 0;">
			<label for="woo-ai-booster-light-<?php echo str_replace('_', '-', $wc_tab_key); ?>-tab"><strong><?php echo $wc_tab_data['title']; ?></strong></label>
			<br>
			<textarea
				name="woo_ai_booster_light_<?php echo str_replace('-', '_', $wc_tab_key); ?>_tab"
				id="woo-ai-booster-light-<?php echo str_replace('_', '-', $wc_tab_key); ?>-tab" rows="4" style="width: 100%;"><?php echo esc_textarea($wc_tab); ?></textarea>
			<small><i><?php _e('Rimuovere il testo per rigenerare il contenuto!', 'woo-ai-booster'); ?></i></small>
		</div>

	<?php

		}

		wp_nonce_field('woo_ai_booster_light_product_tabs_nonce', 'woo_ai_booster_light_product_tabs_nonce');

	}


	function woo_ai_booster_light_product_tabs_save_metabox($post_id, $post, $update) {

		if (!isset($_POST['woo_ai_booster_light_product_tabs_nonce']) ||
			!wp_verify_nonce($_POST['woo_ai_booster_light_product_tabs_nonce'], 'woo_ai_booster_light_product_tabs_nonce')) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		$wc_tabs = woo_ai_booster_light_helper_get_woo_tabs();

		foreach ($wc_tabs as $wc_tab_key => $wc_tab_data) {

			if (isset($_POST['woo_ai_booster_light_' . str_replace('-', '_', $wc_tab_key) . '_tab'])) {
				update_post_meta($post_id, 'woo_ai_booster_light_' . str_replace('-', '_', $wc_tab_key) . '_tab', sanitize_textarea_field($_POST['woo_ai_booster_light_' . str_replace('-', '_', $wc_tab_key) . '_tab']));
			}

		}

	}

	add_action('save_post_product', 'woo_ai_booster_light_product_tabs_save_metabox', 10, 3);