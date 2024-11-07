<?php

	function woo_ai_booster_light_chatgpt_connection($request_type, $prompt = null) {

		$options = get_option('woo_ai_booster_light_settings');

		$api_key = array_key_exists('chat_gpt_ai_api_key', $options) ? $options['chat_gpt_ai_api_key'] : null;

		if (empty($request_type) || empty($prompt) || empty($api_key)) return;

        $headers = array(
            "Authorization: Bearer {$api_key}",
            "Content-Type: application/json"
        );

        if ($request_type == 'chat') {

            $url = 'https://api.openai.com/v1/chat/completions';

            $model = "gpt-4o";

            // Parametri per la restituzione dell'output
            $data = [];
            $data["model"] = $model;
            $data["messages"] = array(array("role" => "user", "content" => $prompt));
            $data["max_tokens"] = 516;
            $data["temperature"] = 1;

            // Sessione cURL
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

	        woo_ai_booster_light_log($result . ' ' . $err);

            if ($err) {
                return null;
            }

            // Decodifica della risposta JSON
            $json_response = json_decode($result, true);

            if (!empty($json_response) && !empty($json_response['error'])) {
                return false;
            }

            return $json_response["choices"][0]["message"]["content"];

        }

        return false;

	}