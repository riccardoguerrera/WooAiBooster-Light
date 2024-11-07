<?php

	function woo_ai_booster_light_helper_help_link($ai = null, $info = null) {

		if (($ai === null) || ($info === null)) return;

		if ($ai === 'chat_gpt') {

			switch ($info) {

				case 'ai-models':
					return 'https://platform.openai.com/docs/models';

				case 'how-generate-api-key':
					return 'https://platform.openai.com/api-keys';

				default:
					break;

			}

		}

	}