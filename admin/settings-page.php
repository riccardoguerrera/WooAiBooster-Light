<?php

    function woo_ai_booster_light_options_page() {

        add_menu_page(
            __('Woo AI Booster', 'woo-ai-booster'),
            __('Woo AI Booster', 'woo-ai-booster'),
            'manage_options',
            'woo_ai_booster_light',
            'woo_ai_booster_light_option_page',
            'dashicons-admin-generic',
            50
        );

	}

    add_action('woocommerce_init', 'woo_ai_booster_light_options_page');


    function woo_ai_booster_light_option_page() {

        if (!current_user_can('manage_options')) {
            return;
        }

	?>

        <div class="wrap">
            <h1><?php esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post" id="waibl-ai-prompt">

	<?php

        // Security field
        settings_fields('woo_ai_booster_light_options');

		do_settings_sections('woo_ai_booster_light');

		$ai = (get_option('waibl_ai')) ? get_option('waibl_ai') : null;

        if (empty($ai)) return;

    	$wc_tabs = woo_ai_booster_light_helper_get_woo_tabs();

		foreach ($ai as $ai_key => $ai_value) {

	?>

				<div class="ai-section <?php echo str_replace('_', '-', $ai_key); ?>" style="display: none;">

	<?php

			do_settings_sections('woo_ai_booster_light_' . str_replace('-', '_', $ai_key));

	?>

					<div class="wc-tabs" style="margin-top: 50px;">

	<?php

			$default_open = true;

			foreach ($wc_tabs as $wc_tab_key => $wc_tab_value) {

	?>

						<button class="wc-tabs-link<?php echo ($default_open) ? ' active' : ''; ?>" data-wctab="<?php echo str_replace('_', '-', $wc_tab_key); ?>"><?php echo $wc_tab_value['title']; ?></button>

	<?php

				$default_open = false;

			}

	?>

					</div>

	<?php

			$default_open = true;

			foreach ($wc_tabs as $wc_tab_key => $wc_tab_value) {

	?>

					<div id="wc-tab-section-<?php echo str_replace('_', '-', $wc_tab_key); ?>-<?php echo str_replace('_', '-', $ai_key); ?>-ai" class="wc-tab-section <?php echo str_replace('_', '-', $ai_key . ' ' . $wc_tab_key); ?>"<?php echo (!$default_open) ? ' style="display: none;"' : '';?>>

	<?php

						do_settings_sections('woo_ai_booster_light_' . str_replace('-', '_', $ai_key) . '_wc_' . str_replace('-', '_', $wc_tab_key) . '_tab');

	?>

					</div>

	<?php

				$default_open = false;

			}

	?>

				</div>

	<?php

		}

        submit_button(__('Salva Impostazioni', 'woo-ai-booster'));

	?>

            </form>
        </div>

	<?php

    }


    function woo_ai_booster_light_settings_init() {

        register_setting('woo_ai_booster_light_options', 'woo_ai_booster_light_settings');

        $ai = (get_option('waibl_ai')) ? get_option('waibl_ai') : null;

        if (empty($ai)) return;

		$wc_tabs = woo_ai_booster_light_helper_get_woo_tabs();

	    add_settings_section(
		    'woo_ai_booster_light_section',
		    __('Configurazione guidata del Prompt per IA', 'woo-ai-booster'),
		    null,
		    'woo_ai_booster_light'
	    );

	    add_settings_field(
		    'woo_ai_booster_light_ai_field',
		    __('Seleziona IA', 'woo-ai-booster'),
		    'woo_ai_booster_light_ai_field_cb',
		    'woo_ai_booster_light',
		    'woo_ai_booster_light_section',
		    array('ai' => $ai)
	    );

        add_settings_field(
		    'woo_ai_booster_light_prompt_language_output_response_field',
		    __('Seleziona una lingua', 'woo-ai-booster'),
		    'woo_ai_booster_light_prompt_language_output_response_field_cb',
		    'woo_ai_booster_light',
		    'woo_ai_booster_light_section',
		    array('ai' => $ai)
	    );

        foreach ($ai as $ai_key => $ai_value) {

	        $ai_key = str_replace('-', '_', $ai_key);

	        add_settings_section(
		        'woo_ai_booster_light_' . $ai_key . '_section',
		        __('Generazione del prompt per ', 'woo-ai-booster') . $ai_value,
		        null,
		        'woo_ai_booster_light_' . $ai_key
	        );

			/**/

            add_settings_field(
                'woo_ai_booster_light_ai_' . $ai_key . '_api_key_field',
                __('Inserisci la API KEY', 'woo-ai-booster'),
                'woo_ai_booster_light_ai_api_key_field_cb',
                'woo_ai_booster_light_' . $ai_key,
	            'woo_ai_booster_light_' . $ai_key . '_section',
                array('ai' => $ai_key)
            );

            add_settings_field(
                'woo_ai_booster_light_max_chars_for_' . $ai_key . '_ai_field',
                __('Numero massimo di caratteri', 'woo-ai-booster'),
                'woo_ai_booster_light_generate_output_max_chars_field_cb',
                'woo_ai_booster_light_' . $ai_key,
                'woo_ai_booster_light_' . $ai_key . '_section',
                array('ai' => $ai_key)
            );

	        add_settings_field(
		        'woo_ai_booster_light_shop_target_description_for_' . $ai_key . '_ai_field',
		        __('Descrizione dello Shop', 'woo-ai-booster'),
		        'woo_ai_booster_light_shop_target_description_field_cb',
		        'woo_ai_booster_light_' . $ai_key,
		        'woo_ai_booster_light_' . $ai_key . '_section',
		        array('ai' => $ai_key)
	        );

			foreach ($wc_tabs as $wc_tab_key => $wc_tab_value) {

				$wc_tab_key = str_replace('-', '_', $wc_tab_key);

                add_settings_section(
                    'woo_ai_booster_light_' . $ai_key . '_wc_' . $wc_tab_key . '_tab_section',
	                '<span style="font-size: 16px;">' . __('Informazioni specifiche per la Tab di Woocommerce ', 'woo-ai-booster') . '"' . $wc_tab_value['title'] . '"</span>',
                    null,
                    'woo_ai_booster_light_' . $ai_key . '_wc_' . $wc_tab_key . '_tab'
                );

                add_settings_field(
                    'woo_ai_booster_light_specific_wc_' . $wc_tab_key . '_tab_instructions_for_' . $ai_key . '_ai_field',
                    __('Istruzioni supplementari', 'woo-ai-booster'),
                    'woo_ai_booster_light_specific_wc_tab_instructions_field_cb',
                    'woo_ai_booster_light_' . $ai_key . '_wc_' . $wc_tab_key . '_tab',
                    'woo_ai_booster_light_' . $ai_key . '_wc_' . $wc_tab_key . '_tab_section',
                    array('ai' => $ai_key, 'wc_tab' => ['key' => $wc_tab_key, 'value' => $wc_tab_value['title']])
                );

            }

        }

    }

    add_action('admin_init', 'woo_ai_booster_light_settings_init');


	/**/


    function woo_ai_booster_light_ai_field_cb($args) {

        $ai = $args['ai'];

        $options = get_option('woo_ai_booster_light_settings');

	?>

		<div>

			<select name="woo_ai_booster_light_settings[ai]" id="ai-select">
				<option value="">-- <?php _e('IA disponibili', 'woo-ai-booster'); ?> --</option>
			<?php foreach ($ai as $key => $value) { ?>
				<option value="<?php echo $key; ?>" <?php echo $options['ai'] === $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
			<?php } ?>
			</select>

		</div>

        <br>

        <details>
            <summary><?php _e('Seleziona una IA', 'woo-ai-booster'); ?></summary>
            <div class="info">
                <p><?php _e('Chat-GPT è un chat bot basato su intelligenza artificiale e apprendimento automatico, sviluppato da OpenAI e specializzato nella conversazione con un utente umano. ', 'woo-ai-booster'); ?></p>
            </div>
		</details>

	<?php

    }


    function woo_ai_booster_light_prompt_language_output_response_field_cb($args) {

        $options = get_option('woo_ai_booster_light_settings');

        $languages = include_once(WAIBL_PATH . '/vendor/umpirsky/language-list/data/' . get_locale() . '/language.php');

        if (empty($languages)) return;

    ?>

        <div>

            <select name="woo_ai_booster_light_settings[prompt_language_output_response]" id="prompt-language-output-response">
                <option value="">-- <?php _e('Seleziona una lingua', 'woo-ai-booster'); ?> --</option>
                <?php foreach ($languages as $language_key => $language_value) { ?>
                    <option value="<?php echo $language_key; ?>" <?php echo $options['prompt_language_output_response'] === $language_key ? 'selected' : ''; ?>><?php echo ucfirst($language_value); ?></option>
                <?php } ?>
            </select>

        </div>

        <br>

        <details>
            <summary><?php _e('Seleziona una lingua', 'woo-ai-booster'); ?></summary>
			<div class="info">
				<p><?php _e('Il contenuto sarà generato dalla IA nella lingua selezionata. ', 'woo-ai-booster'); ?></p>
			</div>
        </details>

    <?php

    }


    function woo_ai_booster_light_ai_api_key_field_cb($args) {

        $options = get_option('woo_ai_booster_light_settings');

	?>

		<div class="<?php echo str_replace('_', '-', $args['ai']); ?>">

			<input
				type="password"
				name="woo_ai_booster_light_settings[<?php echo str_replace('-', '_', $args['ai']); ?>_ai_api_key]"
				id="<?php echo str_replace('_', '-', $args['ai']); ?>-ai-api-key"
				value="<?php echo !empty($options[str_replace('-', '_', $args['ai']) . '_ai_api_key']) ? $options[str_replace('-', '_', $args['ai']) . '_ai_api_key'] : ''; ?>"
				autocomplete="off"
				placeholder="<?php _e('AI API KEY', 'woo-ai-booster'); ?>">

		</div>

        <br>

        <details>
            <summary><?php _e('Inserisci la chiave API per la IA scelta', 'woo-ai-booster'); ?></summary>
			<div class="info">
				<p><?php _e('Una Chiave API è un codice univoco utilizzato per autenticare e autorizzare l\'accesso a un\'API software. Funziona come una "password" che consente di collegarsi al servizio scelto e interagire con il modello di IA, garantendo che solo utenti autorizzati possano inviare richieste e ricevere risposte. ', 'woo-ai-booster'); ?></p>
				<p><?php _e('Per generare una Chiave API ', 'woo-ai-booster'); ?><u><a href="<?php echo woo_ai_booster_light_helper_help_link(str_replace('-', '_', $args['ai']), 'how-generate-api-key'); ?>" rel="nofollow" target="_blank"><?php _e('clicca qui!', 'woo-ai-booster'); ?></a></u></p>
			</div>
        </details>

	<?php

    }


    function woo_ai_booster_light_generate_output_max_chars_field_cb($args) {

        $options = get_option('woo_ai_booster_light_settings');

    ?>

        <div class="<?php echo str_replace('_', '-', $args['ai']); ?>">

            <input
                type="number"
                name="woo_ai_booster_light_settings[generate_output_max_chars_for_<?php echo str_replace('-', '_', $args['ai']); ?>_ai]"
                id="<?php echo 'generate-output-max-chars-for-' . str_replace('_', '-', $args['ai']); ?>-ai"
                min="512"
                max="2048"
                step="8"
                value="<?php echo (!empty($options['generate_output_max_chars_for_' . str_replace('-', '_', $args['ai']) . '_ai'])) ? $options['generate_output_max_chars_for_' . str_replace('-', '_', $args['ai']) . '_ai'] : 512; ?>">

        </div>

        <br>

        <details>
            <summary><?php _e('Imposta la lunghezza del contenuto da generare', 'woo-ai-booster'); ?></summary>
            <div class="info">
                <p><?php _e('Maggiore sarà il numero di caratteri del testo da generare maggiore sarà il costo per singola chiamate alle API del IA.', 'woo-ai-booster'); ?></p>
            </div>
        </details>

    <?php

    }


	function woo_ai_booster_light_shop_target_description_field_cb($args) {

		$options = get_option('woo_ai_booster_light_settings');

	?>

		<div class="<?php echo str_replace('_', '-', $args['ai']); ?>">

			<textarea
				name="woo_ai_booster_light_settings[shop_target_description_for_<?php echo str_replace('-', '_', $args['ai']) ?>_ai]"
				id="<?php echo 'shop-target-description-for-' . str_replace('_', '-', $args['ai']) . '-ai'; ?>"
				autocomplete="off"
				placeholder="<?php _e('Descrizione', 'woo-ai-booster'); ?>"
				rows="4" cols="50"><?php echo (!empty($options['shop_target_description_for_' . str_replace('-', '_', $args['ai']) . '_ai'])) ? $options['shop_target_description_for_' . str_replace('-', '_', $args['ai']) . '_ai'] : ''; ?></textarea>

		</div>

        <br>

        <details>
            <summary><?php _e('Descrivi la tipologia di prodotti che vende il tuo negozio e il target di utenti', 'woo-ai-booster'); ?></summary>
			<div class="info">
                <p><?php _e('Nella descrizione cerca di essere il più chiaro ed esaustivo possibile, per ottenere una maggiore precisione sui contenuti generati.', 'woo-ai-booster'); ?></p>
			</div>
        </details>

	<?php

	}


	function woo_ai_booster_light_specific_wc_tab_instructions_field_cb($args) {

		$options = get_option('woo_ai_booster_light_settings');

    ?>

		<div class="<?php echo str_replace('_', '-', $args['ai']); ?>">

			<textarea
				name="woo_ai_booster_light_settings[specific_wc_<?php echo str_replace('-', '_', $args['wc_tab']['key']); ?>_tab_instructions_for_<?php echo str_replace('-', '_', $args['ai']); ?>_ai]"
				id="<?php echo 'specific-wc-' . str_replace('_', '-', $args['wc_tab']['key']) . '-tab-instructions-for-' . str_replace('_', '-', $args['ai']) . '-ai'; ?>"
				class="not-require"
				placeholder="<?php _e('Descrizione', 'woo-ai-booster'); ?>"
				autocomplete="off"
				rows="4" cols="50"><?php echo (!empty($options['specific_wc_' . str_replace('-', '_', $args['wc_tab']['key']) . '_tab_instructions_for_' . str_replace('-', '_', $args['ai']) . '_ai'])) ? $options['specific_wc_' . str_replace('-', '_', $args['wc_tab']['key']) . '_tab_instructions_for_' . str_replace('-', '_', $args['ai']) . '_ai'] : ''; ?></textarea>

		</div>

        <br>

        <details>
            <summary><?php _e('Inserire istruzioni specifiche per questa tab da incorporare nel prompt destinato alla IA', 'woo-ai-booster'); ?></summary>
			<div class="info">
                <p><?php _e('Istruzioni supplementari da tenere in considerazione per questa specifica tab di Woocommerce. ', 'woo-ai-booster'); ?></p>
			</div>
        </details>

    <?php

	}