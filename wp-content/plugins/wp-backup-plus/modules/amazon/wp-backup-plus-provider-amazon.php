<?php
/*
 Plugin Name: WP Backup Plus Provider - Amazon S3
 Plugin URI: http://wpbackupplus.com
 Description: Register Amazon S3 as a provider for the WP Backup Plus plugin.
 Version: 1.0.0-RC1
 Author: WP Backup Plus
 Author URI: http://wpbackupplus.com
 */

if (!class_exists('WP_Backup_Plus_Provider_Amazon')) {
	class WP_Backup_Plus_Provider_Amazon {
		/// CONSTANTS

		//// VERSION
		const VERSION = '1.0.1';

		//// KEYS
		const SETTINGS_KEY = '_wpbp_provider_amazon_settings';

		//// CACHE
		const CACHE_PERIOD = 86400;
		// 24 HOURS

		/// DATA STORAGE
		private static $default_settings = array();

		public static function init() {
			self::add_actions();
			self::add_filters();
		}

		private static function add_actions() {
			add_action('wp_backup_plus_compatibility_table_amazon', array(__CLASS__, 'display_compatibility_rows'));
			add_action('wp_backup_plus_download_backup_amazon', array(__CLASS__, 'download_backup'));
			add_action('wp_backup_plus_enqueue_resources', array(__CLASS__, 'enqueue_resources'));
			add_action('wp_backup_plus_method_settings_amazon', array(__CLASS__, 'display_settings'));
			add_action('wp_backup_plus_perform_backup_amazon', array(__CLASS__, 'perform_backup'), 10, 2);
			add_action('wp_backup_plus_save_settings', array(__CLASS__, 'process_settings_save'), 10, 2);
		}

		private static function add_filters() {
			add_filter('wp_backup_plus_provider_amazon_pre_settings_save', array(__CLASS__, 'sanitize_settings'));

			add_filter('wp_backup_plus_backup_file_amazon', array(__CLASS__, 'retrieve_backup_file'), 10, 2);
			add_filter('wp_backup_plus_backup_methods', array(__CLASS__, 'add_backup_methods'));
			add_filter('wp_backup_plus_previous_backups_amazon', array(__CLASS__, 'add_previous_backups'));
			add_filter('wp_backup_plus_meets_compatibility_requirements_amazon', array(__CLASS__, 'meets_compatibility_requirements'));
			add_filter('wp_backup_plus_schedules', array(__CLASS__, 'add_backup_schedules'));
		}

		/// PLUGIN SPECIFIC CALLBACKS

		public static function add_backup_methods($methods) {
			$methods['amazon'] = __('Amazon S3');

			return $methods;
		}

		public static function add_backup_schedules($schedules) {

			return $schedules;
		}

		public static function add_previous_backups($backups) {
			$settings = self::get_settings();

			$objects = self::list_objects($settings['bucket']);
			if (!is_wp_error($objects, true)) {
				foreach($objects as $object) {
					$object->Bucket = $settings['bucket'];
					$object->Name = $object->Key;
					$backups[] = $object;
				}
			}

			return $backups;
		}

		/// CALLBACKS

		public static function download_backup($item) {
			$object_url = self::get_object_url($item->Bucket, $item->Key);

			if(is_wp_error($object_url)) {
				// Problem?
			} else {
				wp_redirect($object_url);
				exit;
			}
		}

		public static function enqueue_resources() {
			wp_enqueue_script('wp-backup-plus-provider-amazon-backend', plugins_url('resources/backend/wp-backup-plus-provider-amazon.js', __FILE__), array('jquery'), self::VERSION);
		}

		public static function meets_compatibility_requirements($meets) {
			return $meets && !in_array(false, self::get_compatibility_requirements(), true);
		}

		public static function perform_backup($backup_errors, $zip_file_path) {
			$settings = self::get_settings();
			$create_object = self::create_object($settings['bucket'], basename($zip_file_path), $zip_file_path);

			if (is_wp_error($create_object)) {
				$backup_errors->add($create_object->get_error_code(), $create_object->get_error_message());
			}
		}

		public static function process_settings_save($wp_backup_plus_settings, $request_data) {
			$settings = apply_filters('wp_backup_plus_provider_amazon_pre_settings_save', (array)$request_data['wp-backup-plus-provider-amazon']);
			$settings = self::set_settings($settings);
		}

		public static function retrieve_backup_file($file_path, $item) {
			$object_url = self::get_object_url($item->Bucket, $item->Key);

			if(!is_wp_error($object_url)) {
				$response = wp_remote_get($object_url, array('timeout' => 60*20));
				if(!is_wp_error($response)) {
					$result = wp_upload_bits('backup.zip', null, wp_remote_retrieve_body($response));

					$file_path = isset($result['file']) ? $result['file'] : false;
				}
			}

			return $file_path;
		}

		public static function sanitize_settings($settings) {
			if ('create' == $settings['bucket-action']) {
				$bucket_name = self::create_bucket($settings['bucket-name']);
				$settings['bucket'] = is_wp_error($bucket_name) ? '' : $bucket_name;
			}

			unset($settings['bucket-action']);

			return $settings;
		}

		/// DISPLAY CALLBACKS

		public static function display_compatibility_rows() {
			extract(self::get_compatibility_requirements());

			include('views/backend/compatibility.php');
		}

		public static function display_settings() {
			$settings = self::get_settings();

			$buckets = self::list_buckets($settings['access-key-id'], $settings['secret-access-key']);

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

			$has_credentials = !empty($settings['access-key-id']) && !empty($settings['secret-access-key']);
			$credentials_valid = !is_wp_error(self::list_buckets());

			$has_bucket = !empty($settings['bucket']);
			$bucket_valid = !is_wp_error(self::list_objects($settings['bucket']));

			return compact('has_credentials', 'credentials_valid', 'has_bucket', 'bucket_valid');
		}

		//// LIBRARY LOADING

		/**
		 * @return AmazonS3 | WP_Error
		 */
		private static function amazon_s3() {
			require_once ('lib/aws-sdk/sdk.class.php');
			$settings = self::get_settings();

			try {
				$result = new AmazonS3( array('certificate_authority' => true, 'key' => $settings['access-key-id'], 'secret' => $settings['secret-access-key']));
			} catch(Exception $e) {
				$result = new WP_Error($e->getCode(), $e->getMessage());
			}

			return $result;
		}

		//// API CALLS

		//// BUCKETS

		private static function create_bucket($bucket_name) {
			$amazon_s3 = self::amazon_s3();

			if (is_wp_error($amazon_s3)) {
				$result = $amazon_s3;
			} else {
				$response = $amazon_s3->create_bucket($bucket_name, AmazonS3::REGION_US_E1);

				if (200 == $response->status) {
					$result = $bucket_name;
				} else {
					$result = new WP_Error($response->body->Code->to_string(), $response->body->Message->to_string());
				}
			}

			return $result;
		}

		private static function list_buckets() {
			$amazon_s3 = self::amazon_s3();

			if (is_wp_error($amazon_s3)) {
				$result = $amazon_s3;
			} else {
				$response = $amazon_s3->list_buckets();

				if (200 == $response->status) {
					$result = array();

					if (isset($response->body->Buckets->Bucket)) {
						foreach ($response->body->Buckets->Bucket as $bucket_info) {
							$result[] = $bucket_info->to_stdClass();
						}
					}
				} else {
					$result = new WP_Error($response->body->Code->to_string(), $response->body->Message->to_string());
				}
			}

			return $result;
		}

		///// OBJECTS

		private static function create_object($bucket_name, $object_name, $object_path) {
			$amazon_s3 = self::amazon_s3();

			if (is_wp_error($amazon_s3)) {
				$result = $amazon_s3;
			} else {
				$response = $amazon_s3->create_object($bucket_name, $object_name, array('fileUpload' => $object_path));

				if (200 == $response->status) {
					$result = true;
				} else {
					$result = new WP_Error($response->body->Code->to_string(), $response->body->Message->to_string());
				}
			}

			return $result;
		}

		private static function get_object_url($object_bucket, $object_key) {
			$amazon_s3 = self::amazon_s3();

			if(is_wp_error($amazon_s3)) {
				$result = $amazon_s3;
			} else {
				try {
					$result = $amazon_s3->get_object_url($object_bucket, $object_key, time() + 60);
				} catch(Exception $e) {
					$result = new WP_Error($e->getCode(), $e->getMessage());
				}
			}

			return $result;
		}

		private static function list_objects($bucket_name) {
			$amazon_s3 = self::amazon_s3();

			if (is_wp_error($amazon_s3)) {
				$result = $amazon_s3;
			} else {
				try {
					$response = $amazon_s3->list_objects($bucket_name);

					if (200 == $response->status) {
						$result = array();

						if (isset($response->body->Contents)) {
							foreach ($response->body->Contents as $object_info) {
								$result[] = $object_info->to_stdClass();
							}
						}
					} else {
						$result = new WP_Error($response->body->Code->to_string(), $response->body->Message->to_string());
					}
				} catch(Exception $e) {
					$result = new WP_Error($e->getCode(), $e->getMessage());
				}
			}

			return $result;
		}


	}

	add_action('wp_backup_plus_init', array('WP_Backup_Plus_Provider_Amazon', 'init'));
}
