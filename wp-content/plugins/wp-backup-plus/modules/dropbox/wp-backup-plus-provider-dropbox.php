<?php
/*
 Plugin Name: WP Backup Plus Provider - Dropbox
 Plugin URI: http://wpbackupplus.com
 Description: Register Dropbox as a provider for the WP Backup Plus plugin.
 Version: 1.0.0-RC1
 Author: WP Backup Plus
 Author URI: http://wpbackupplus.com
 */

if (!class_exists('WP_Backup_Plus_Provider_Dropbox')) {
	class WP_Backup_Plus_Provider_Dropbox {
		/// CONSTANTS

		//// VERSION
		const VERSION = '1.0.1';

		//// KEYS
		const SETTINGS_KEY = '_wpbp_provider_dropbox_settings';

		//// CACHE
		const CACHE_PERIOD = 86400;
		// 24 HOURS

		/// DATA STORAGE
		private static $default_settings = array('request-token' => false, 'access-token' => false);
		private static $dropbox_api = null;
		private static $dropbox_oauth = null;

		public static function init() {
			self::add_actions();
			self::add_filters();
		}

		private static function add_actions() {
			add_action('wp_backup_plus_settings_loaded', array(__CLASS__, 'process_dropbox_callback'));

			add_action('wp_backup_plus_compatibility_table_dropbox', array(__CLASS__, 'display_compatibility_rows'));
			add_action('wp_backup_plus_download_backup_dropbox', array(__CLASS__, 'download_backup'));
			add_action('wp_backup_plus_enqueue_resources', array(__CLASS__, 'enqueue_resources'));
			add_action('wp_backup_plus_method_settings_dropbox', array(__CLASS__, 'display_settings'));
			add_action('wp_backup_plus_perform_backup_dropbox', array(__CLASS__, 'perform_backup'), 10, 2);
			add_action('wp_backup_plus_save_settings', array(__CLASS__, 'process_settings_save'), 10, 2);
		}

		private static function add_filters() {
			add_filter('wp_backup_plus_provider_dropbox_pre_settings_save', array(__CLASS__, 'sanitize_settings'));

			add_filter('wp_backup_plus_backup_file_dropbox', array(__CLASS__, 'retrieve_backup_file'), 10, 2);
			add_filter('wp_backup_plus_backup_methods', array(__CLASS__, 'add_backup_methods'));
			add_filter('wp_backup_plus_previous_backups_dropbox', array(__CLASS__, 'add_previous_backups'));
			add_filter('wp_backup_plus_meets_compatibility_requirements_dropbox', array(__CLASS__, 'meets_compatibility_requirements'));
			add_filter('wp_backup_plus_schedules', array(__CLASS__, 'add_backup_schedules'));
		}

		/// PLUGIN SPECIFIC CALLBACKS

		public static function add_backup_methods($methods) {
			$methods['dropbox'] = __('Dropbox');

			return $methods;
		}

		public static function add_backup_schedules($schedules) {

			return $schedules;
		}

		public static function add_previous_backups($backups) {
			$files = self::list_files();

			if(!is_wp_error($files) && is_array($files['contents'])) {
				foreach($files['contents'] as $file_metadata) {
					if(0 === strpos($file_metadata['path'], '/wpbp-')) {
						$object = new stdClass;
						foreach($file_metadata as $key => $value) {
							$object->$key = $value;
						}
						$object->Name = trim($object->path, '/');

						$backups[] = $object;
					}
				}
			}

			return $backups;
		}

		/// CALLBACKS

		public static function download_backup($item) {
			$file_data = self::get_file($item->path);

			if(is_wp_error($file_data)) {
				wp_die('Could not download backup.');
			} else {
				header('Content-Type: ' . $item->mime_type);
				header('Content-Disposition: attachment; filename="backup.zip"');
				echo $file_data;
			}
		}

		public static function enqueue_resources() {

		}

		public static function meets_compatibility_requirements($meets) {
			return $meets && !in_array(false, self::get_compatibility_requirements(), true);
		}

		public static function perform_backup($backup_errors, $zip_file_path) {
			$put_file = self::put_file($zip_file_path);

			if (is_wp_error($put_file)) {
				$backup_errors->add($put_file->get_error_code(), $put_file->get_error_message());
			}
		}

		public static function process_dropbox_callback($data) {
			if(isset($data['dropbox-oauth-callback']) && 'true' == $data['dropbox-oauth-callback'] && isset($data['oauth_token']) && isset($data['uid'])) {
				if(is_wp_error(self::get_access_token())) {
					add_settings_error('general', 'dropbox-access-token', __('Error retrieving access token. Please try again.'), 'error');
				} else {
					add_settings_error('general', 'dropbox-access-token', __('Access token saved.'), 'updated');
				}
				set_transient('settings_errors', get_settings_errors(), 30);

				wp_redirect(add_query_arg(array('settings-updated' => 'true'), wp_backup_plus_get_settings_page_url()));
				exit;
			}
		}

		public static function process_settings_save($wp_backup_plus_settings, $request_data) {
			$settings = apply_filters('wp_backup_plus_provider_dropbox_pre_settings_save', (array)$request_data['wp-backup-plus-provider-dropbox']);
			$settings = self::set_settings($settings);
		}

		public static function retrieve_backup_file($file_path, $item) {
			$file_data = self::get_file($item->path);

			if(!is_wp_error($file_data)) {
				$result = wp_upload_bits('backup.zip', null, $file_data);
				$file_path = isset($result['file']) ? $result['file'] : false;
			}

			return $file_path;
		}

		public static function sanitize_settings($settings) {
			return $settings;
		}

		/// DISPLAY CALLBACKS

		public static function display_compatibility_rows() {
			extract(self::get_compatibility_requirements());

			include('views/backend/compatibility.php');
		}

		public static function display_settings() {
			$settings = self::get_settings();

			if(is_wp_error(self::get_account_info()) || empty($settings['access-token'])) {
				$authorize_url = self::get_authorize_url();
			}

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

			$has_credentials = !empty($settings['app-key']) && !empty($settings['app-secret']);

			$has_access_token = is_array($settings['access-token']);
			$access_token_valid = !is_wp_error(self::get_account_info());

			return compact('has_credentials', 'has_access_token', 'access_token_valid');
		}

		//// LIBRARY LOADING

		private static function dropbox_libraries() {
			require_once ('lib/dropbox/dropbox-api.php');
			require_once ('lib/dropbox/dropbox-exceptions.php');
			require_once ('lib/dropbox/dropbox-oauth.php');
			require_once ('lib/dropbox/dropbox-oauth-wordpress.php');
		}

		private static function get_dropbox_api() {
			self::dropbox_libraries();

			if(null === self::$dropbox_api) {
				$dropbox_oauth = self::get_dropbox_oauth();

				if(is_wp_error($dropbox_oauth)) {
					self::$dropbox_api = $dropbox_oauth;
				} else {
					self::$dropbox_api = new Dropbox_API($dropbox_oauth, Dropbox_API::ROOT_SANDBOX);
				}
			}

			return self::$dropbox_api;
		}

		private static function get_dropbox_oauth() {
			self::dropbox_libraries();

			if(null === self::$dropbox_oauth) {
				$settings = self::get_settings();

				if(empty($settings['app-key']) || empty($settings['app-secret'])) {
					self::$dropbox_oauth = new WP_Error('get-dropbox-oauth', __('The App Key or App Secret settings are empty.'));
				} else {
					self::$dropbox_oauth = new Dropbox_OAuth_WordPress($settings['app-key'], $settings['app-secret']);

					if(!empty($settings['access-token'])) {
						try {
							self::$dropbox_oauth->setToken($settings['access-token']);
						} catch(Exception $e) {
							// Meh?
						}
					}
				}
			}

			return self::$dropbox_oauth;
		}

		//// OAUTH

		private static function get_access_token() {
			$dropbox_oauth = self::get_dropbox_oauth();

			if(is_wp_error($dropbox_oauth)) {
				$result = $dropbox_oauth;
			} else {
				try {
					$settings = self::get_settings();
					$dropbox_oauth->setToken($settings['request-token']);

					$result = $settings['access-token'] = $dropbox_oauth->getAccessToken();
					$settings = self::set_settings($settings);
				} catch(Exception $e) {
					$result = new WP_Error('get-access-token', __('The access token could not be retrieved.'));
				}
			}

			return $result;
		}

		private static function get_authorize_url() {
			$dropbox_oauth = self::get_dropbox_oauth();

			if(is_wp_error($dropbox_oauth)) {
				$result = $dropbox_oauth;
			} else {
				try {
					$settings = self::get_settings();
					$settings['request-token'] = $dropbox_oauth->getRequestToken();
					$settings = self::set_settings($settings);

					$result = $dropbox_oauth->getAuthorizeUrl(urlencode(add_query_arg(array('dropbox-oauth-callback' => 'true'), wp_backup_plus_get_settings_page_url())));
				} catch(Exception $e) {
					$result = new WP_Error('get-authorize-url', __('The authorize URL could not be generated because the client credentials were incorrect.'));
				}
			}

			return $result;
		}

		//// API CALLS

		private static function get_account_info() {
			$dropbox_api = self::get_dropbox_api();

			if(is_wp_error($dropbox_api)) {
				return $dropbox_api;
			} else {
				$account_info = $dropbox_api->getAccountInfo();

				if(isset($account_info['error'])) {
					return new WP_Error('get-account-info', __('The access token is invalid.'));
				} else {
					return $account_info;
				}
			}
		}

		private static function get_file($path) {
			$dropbox_api = self::get_dropbox_api();

			return is_wp_error($dropbox_api) ? $dropbox_api : $dropbox_api->getFile($path);
		}

		private static function list_files() {
			$dropbox_api = self::get_dropbox_api();

			return is_wp_error($dropbox_api) ? $dropbox_api : $dropbox_api->getMetaData('/');
		}

		private static function put_file($file_path) {
			$dropbox_api = self::get_dropbox_api();

			return is_wp_error($dropbox_api) ? $dropbox_api : $dropbox_api->putFile(basename($file_path), $file_path);
		}

	}

	add_action('wp_backup_plus_init', array('WP_Backup_Plus_Provider_Dropbox', 'init'));
}
