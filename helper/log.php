<?php

	function woo_ai_booster_light_log($txt = '') {
		$log_file_path = WAIBL_PATH . '/waibl-debug.log';
		$file_size = filesize($log_file_path) / 1024;
		if ($file_size > 500) {
			unlink($log_file_path);
			file_put_contents($log_file_path, '');
		} else {
			$log = file_get_contents($log_file_path, true);
			$new_log_entry = date('Y-m-d H:i:s') . ' - ' . $txt . "<br><br>" . $log;
			file_put_contents($log_file_path, $new_log_entry);
		}
	}