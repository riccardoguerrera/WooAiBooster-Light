<?php

	/**
	 * Woocommerce IA Booster
	 */


	defined( 'ABSPATH' ) || exit;


	global $post;

    do_action('wc_ai_booster_light_generate_tabs_content');

    $tab_content = get_post_meta(get_the_ID(), 'woo_ai_booster_light_description_tab', true);

    if (empty($tab_content)) {
		the_content();
	} else {
		echo $tab_content;
	}

?>