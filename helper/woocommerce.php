<?php

    function woo_ai_booster_light_helper_get_woo_tabs() {

		$wc_tabs["description"] = array(
			"title" => __("Description", "woocommerce"),
			"priority" => 10,
			"callback" => "woocommerce_product_description_tab"
		);

	    unset($wc_tabs['reviews']);

		return $wc_tabs;

    }
