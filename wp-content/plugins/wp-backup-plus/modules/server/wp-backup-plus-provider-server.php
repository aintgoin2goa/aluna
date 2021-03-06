<?php
/*
 Plugin Name: WP Backup Plus Provider - Amazon S3
 Plugin URI: http://wpbackupplus.com
 Description: Register Amazon S3 as a provider for the WP Backup Plus plugin.
 Version: 1.0.0-RC1
 Author: WP Backup Plus
 Author URI: http://wpbackupplus.com
 */

if (!class_exists('WP_Backup_Plus_Provider_Server')) {
	class WP_Backup_Plus_Provider_Server {
		/// CONSTANTS

		//// VERSION
		const VERSION = '1.0.1';

		//// KEYS
		const SETTINGS_KEY = '_wpbp_provider_server_settings';

		//// CACHE
		const CACHE_PERIOD = 86400;
		// 24 HOURS

		/// DATA STORAGE
		private static $default_settings = array('type' => 'local');
		private static $server = null;

		public static function init() {
			self::add_actions();
			self::add_filters();
			self::initialize_defaults();
		}

		private static function add_actions() {
			add_action('wp_backup_plus_compatibility_table_server', array(__CLASS__, 'display_compatibility_rows'));
			add_action('wp_backup_plus_download_backup_server', array(__CLASS__, 'download_backup'));
			add_action('wp_backup_plus_enqueue_resources', array(__CLASS__, 'enqueue_resources'));
			add_action('wp_backup_plus_method_settings_server', array(__CLASS__, 'display_settings'));
			add_action('wp_backup_plus_perform_backup_server', array(__CLASS__, 'perform_backup'), 10, 2);
			add_action('wp_backup_plus_save_settings', array(__CLASS__, 'process_settings_save'), 10, 2);
		}

		private static function add_filters() {
			add_filter('wp_backup_plus_provider_server_pre_settings_save', array(__CLASS__, 'sanitize_settings'));

			add_filter('wp_backup_plus_backup_file_server', array(__CLASS__, 'retrieve_backup_file'), 10, 2);
			add_filter('wp_backup_plus_backup_methods', array(__CLASS__, 'add_backup_methods'));
			add_filter('wp_backup_plus_previous_backups_server', array(__CLASS__, 'add_previous_backups'));
			add_filter('wp_backup_plus_meets_compatibility_requirements_server', array(__CLASS__, 'meets_compatibility_requirements'));
			add_filter('wp_backup_plus_schedules', array(__CLASS__, 'add_backup_schedules'));
		}

		private static function initialize_defaults() {
			self::$default_settings['local-directory'] = WP_Backup_Plus::convert_to_appropriate_path(WP_Backup_Plus::get_backup_directory());
		}

		/// PLUGIN SPECIFIC CALLBACKS

		public static function add_backup_methods($methods) {
			$methods['server'] = __('Local or Remote Server');

			return $methods;
		}

		public static function add_backup_schedules($schedules) {

			return $schedules;
		}

		public static function add_previous_backups($backups) {
			$settings = self::get_settings();

			$files = self::list_files();
			if (!is_wp_error($files) && is_array($files)) {
				foreach ((array)$files as $file) {
					$backup = new stdClass;
					$backup->Name = $file;

					$backups[] = $backup;
				}
			}

			return $backups;
		}

		/// CALLBACKS

		public static function download_backup($item) {
			$settings = self::get_settings();

			$type = $settings['type'] == 'local' ? 'local' : 'remote';

			switch($type) {
				case 'local' :
					$file_path = path_join($settings['local-directory'], $item->Name);

					header('Content-Type: application/zip');
					header('Content-Disposition: attachment; filename="backup.zip"');
					readfile($file_path);
					exit ;
					break;
				case 'remote' :
					$contents = self::get_file($item->Name);
					if (!is_wp_error($contents)) {
						header('Content-Type: application/zip');
						header('Content-Disposition: attachment; filename="backup.zip"');
						echo $contents;
						exit ;
					}
					break;
			}
		}

		public static function enqueue_resources() {
			wp_enqueue_script('wp-backup-plus-provider-server-backend', plugins_url('resources/backend/wp-backup-plus-provider-server.js', __FILE__), array('jquery'), self::VERSION);
		}

		public static function meets_compatibility_requirements($meets) {
			return $meets && !in_array(false, self::get_compatibility_requirements(), true);
		}

		public static function perform_backup($backup_errors, $zip_file_path) {
			$create_object = self::put_file(basename($zip_file_path), $zip_file_path);

			if (is_wp_error($create_object)) {
				$backup_errors->add($create_object->get_error_code(), $create_object->get_error_message());
			}
		}

		public static function process_settings_save($wp_backup_plus_settings, $request_data) {
			$settings = apply_filters('wp_backup_plus_provider_server_pre_settings_save', (array)$request_data['wp-backup-plus-provider-server']);
			$settings = self::set_settings($settings);
		}

		public static function retrieve_backup_file($file_path, $item) {
			$settings = self::get_settings();

			$type = $settings['type'] == 'local' ? 'local' : 'remote';

			switch($type) {
				case 'local' :
					$destination = path_join(WP_Backup_Plus::get_backup_directory(), time() . '.zip');
					$filesystem = WP_Backup_Plus::get_filesystem(null);
					if ($filesystem->copy(path_join($settings['local-directory'], $item->Name), $destination)) {
						$file_path = $destination;
					}
					break;
				case 'remote' :
					$file_data = self::get_file($item->Name);

					if(!is_wp_error($file_data)) {
						$result = wp_upload_bits('backup.zip', null, $file_data);
						$file_path = isset($result['file']) ? $result['file'] : false;
					}
					break;
			}

			return $file_path;
		}

		public static function sanitize_settings($settings) {

			return $settings;
		}

		/// DISPLAY CALLBACKS

		public static function display_compatibility_rows() {
			extract(self::get_compatibility_requirements());

			$settings = self::get_settings();
			$type = $settings['type'] == 'local' ? 'local' : 'remote';

			include ("views/backend/{$type}-compatibility.php");
		}

		public static function display_settings() {
			$settings = self::get_settings();

			include ('views/backend/settings.php');
		}

		/// SETTINGS

		private static function get_settings() {
			$settings = wp_cache_get(self::SETTINGS_KEY);

			if (!is_array($settings)) {
				$settings = wp_parse_args(get_option(self::SETTINGS_KEY, self::$default_settings), self::$default_settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + self::CACHE_PERIOD);
			}

			return $settings;
		}

		private static function set_settings($settings) {
			if (is_array($settings)) {
				$settings = wp_parse_args($settings, self::$default_settings);
				update_option(self::SETTINGS_KEY, $settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + self::CACHE_PERIOD);
			}

			return $settings;
		}

		/// UTILITY

		private static function get_compatibility_requirements() {
			$settings = self::get_settings();

			$requirements = array();
			if ('local' == $settings['type']) {
				$requirements['directory_exists'] = is_dir($settings['local-directory']);
				$requirements['directory_writable'] = is_writable($settings['local-directory']);
			} else if ('remote' == $settings['type']) {
				$server = self::get_remote_server();

				$requirements['ftp_extension_exists'] = function_exists('ftp_connect');
				$requirements['ftp_credentials_correct'] = !is_wp_error(self::get_remote_server());
				$requirements['path_valid'] = !is_wp_error(self::get_remote_server()) && is_array($server->dirlist($settings['remote-directory'], true));
			}

			return $requirements;
		}

		//// LIBRARY LOADING

		/**
		 * @return WP_Filesystem_FTPext | WP_Error
		 */
		private static function get_remote_server() {
			if (null === self::$server) {
				set_time_limit(0);

				if (!defined('FS_CONNECT_TIMEOUT')) {
					define('FS_CONNECT_TIMEOUT', 10);
				}
				if (!defined('FS_TIMEOUT')) {
					define('FS_TIMEOUT', 10);
				}

				$settings = self::get_settings();

				if (!class_exists('WP_Filesystem_FTPext')) {
					require_once (ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php');
					require_once (ABSPATH . '/wp-admin/includes/class-wp-filesystem-ftpext.php');
				}
				if(!function_exists('wp_tempnam')) {
					require_once (ABSPATH . '/wp-admin/includes/file.php');
				}

				$options = array('hostname' => $settings['host'], 'username' => $settings['username'], 'password' => $settings['password'], );
				$filesystem = new WP_Filesystem_FTPext($options);

				if ($filesystem->connect()) {
					self::$server = $filesystem;
				} else {
					self::$server = new WP_Error('wp-backup-provider-server-get-remote-server', __('Could not connect to server.'));
				}
			}

			return self::$server;
		}

		//// FILES

		private static function put_file($file_name, $file_path) {
			$settings = self::get_settings();

			$type = $settings['type'] == 'local' ? 'local' : 'remote';

			switch($type) {
				case 'local' :
					$filesystem = WP_Backup_Plus::get_filesystem();
					$result = $filesystem->copy($file_path, path_join($settings['local-directory'], $file_name));
					break;
				case 'remote' :
					$server = self::get_remote_server();

					if (is_wp_error($server)) {
						$result = $server;
					} else {
						$server->put_contents(path_join($settings['remote-directory'], $file_name), file_get_contents($file_path));
					}
					break;
			}

			return $result;
		}

		private static function get_file($file_name) {
			$settings = self::get_settings();

			$type = $settings['type'] == 'local' ? 'local' : 'remote';

			switch($type) {
				case 'remote' :
					$server = self::get_remote_server();

					if (is_wp_error($server)) {
						$result = $server;
					} else {
						$result = $server->get_contents(path_join($settings['remote-directory'], $file_name));
						error_log(print_r($result,true));
					}
					break;
			}

			return $result;
		}

		private static function list_files() {
			$settings = self::get_settings();

			$type = $settings['type'] == 'local' ? 'local' : 'remote';

			switch($type) {
				case 'local' :
					$filesystem = WP_Backup_Plus::get_filesystem();

					$file_list = $filesystem->dirlist($settings['local-directory']);
					if(!is_array($file_list)) {
						$file_list = array();
					}

					$result = array();
					foreach ($file_list as $file) {
						if ('f' == $file['type'] && 0 === strpos($file['name'], 'wpbp-')) {
							$result[] = $file['name'];
						}
					}

					break;
				case 'remote' :
					$server = self::get_remote_server();

					if (is_wp_error($server)) {
						$result = $server;
					} else {
						$files = $server->dirlist($settings['remote-directory']);
						if (is_array($files)) {

							$result = array();
							foreach ($files as $file) {
								$result[] = $file['name'];
							}
						}
					}
					break;
			}

			return $result;
		}

	}

	add_action('wp_backup_plus_init', array('WP_Backup_Plus_Provider_Server', 'init'));
}
