<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('WP Backup Plus - Settings'); ?></h2>
	<?php settings_errors(); ?>

	<p><?php printf(__('If you need support, please contact the plugin maintainer at <a href="%s">%s</a>'), 'http://ryanshaw.me/support/', 'http://ryanshaw.me/support/'); ?></p>

	<form method="post" action="<?php esc_attr_e(esc_url(add_query_arg(array()))); ?>">
		<h3><?php _e('General Settings'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="wp-backup-plus-methods"><?php _e('Methods'); ?></label></th>
					<td>
						<ul style="margin: 0;">
							<?php foreach(self::$backup_methods as $method_key => $method_value) { ?>
							<li>
								<label>
									<input class="wp-backup-plus-method" <?php if($method_key == 'manual') { echo 'disabled="disabled"'; } ?> <?php checked(in_array($method_key, $settings['methods']), true); ?> type="checkbox" name="wp-backup-plus[methods][]" id="wp-backup-plus-methods-<?php esc_attr_e($method_key); ?>" value="<?php esc_attr_e($method_key); ?>" />
									<?php esc_html_e($method_value); ?>
								</label>
							</li>
							<?php } ?>
						</ul>

						<small><?php _e('Select the methods you wish to use for your scheduled and on-demand backups.'); ?></small>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp-backup-plus-schedule"><?php _e('Schedule'); ?></label></th>
					<td>
						<select name="wp-backup-plus[schedule]" id="wp-backup-plus-schedule">
							<?php foreach(self::$backup_schedules as $schedule_key => $schedule_data) { ?>
							<option <?php selected($settings['schedule'], $schedule_key); ?> value="<?php esc_attr_e($schedule_key); ?>"><?php esc_html_e($schedule_data['name']); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp-backup-plus-notification"><?php _e('Notification'); ?></label></th>
					<td>
						<label>
							<input <?php checked($settings['notification'], 'yes'); ?> type="checkbox" name="wp-backup-plus[notification]" id="wp-backup-plus-notification" value="yes" />
							<?php _e('I want to be notified via email whenever a backup is created via the scheduled process'); ?>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp-backup-plus-email"><?php _e('Notification Email'); ?></label></th>
					<td>
						<label>
							<input type="text" class="code regular-text" name="wp-backup-plus[email]" id="wp-backup-plus-email" value="<?php esc_attr_e($settings['email']); ?>" /><br />
							<small><?php _e('The email you enter above will be used for email notifications.'); ?></small>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e('Content Settings'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Exclude Directories From Backups'); ?></th>
					<td>
						<ul style="margin: 0;">
							<li>
								<label>
									<input <?php checked(true, in_array('/wp-content/', $settings['exclude-directories-named'])); ?> type="checkbox" name="wp-backup-plus[exclude-directories-named][]" value="/wp-content/" />
									<code>/wp-content/</code>
								</label>
							</li>
							<li>
								<label>
									<input <?php checked(true, in_array('/wp-admin/', $settings['exclude-directories-named'])); ?> type="checkbox" name="wp-backup-plus[exclude-directories-named][]" value="/wp-admin/" />
									<code>/wp-admin/</code>
								</label>
							</li>
							<li>
								<label>
									<input <?php checked(true, in_array('/wp-includes/', $settings['exclude-directories-named'])); ?> type="checkbox" name="wp-backup-plus[exclude-directories-named][]" value="/wp-includes/" />
									<code>/wp-includes/</code>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp-backup-plus-additional-exclusions"><?php _e('Additional Excluded Directories'); ?></label></th>
					<td>
						<textarea class="code large-text" rows="5" name="wp-backup-plus[additional-exclusions]" id="wp-backup-plus-additional-exclusions"><?php esc_html_e($settings['additional-exclusions']); ?></textarea><br />
						<small><?php _e('Enter one directory per line that you wish to exclude (like <code>/wp-content/uploads/</code>)'); ?></small>
					</td>
				</tr>
			</tbody>
		</table>

		<?php if(defined('WP_BACKUP_PLUS_ALLOW_MYSQLDUMP') && WP_BACKUP_PLUS_ALLOW_MYSQLDUMP) { ?>
		<h3><?php _e('MySQL Settings'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="wp-backup-plus-mysqldump"><?php _e('MySQL Dump Location'); ?></label></th>
					<td>
						<input type="text" class="code regular-text" name="wp-backup-plus[mysqldump]" id="wp-backup-plus-mysqldump" value="<?php esc_attr_e($settings['mysqldump']); ?>" /><br />
						<small><?php _e('If you have a large database or want to speed up your database backups, you can enter the path to your server\'s <code>mysqldump</code> binary to provide a better experience.'); ?></small><br />
						<small><?php _e('The plugin has attempted to auto-detect an appropriate value, but if the field is empty you should be able to log in to your server and run the command <code>which mysqldump</code> from the terminal to obtain the correct value.'); ?></small>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>

		<?php foreach(self::$backup_methods as $backup_key => $backup_name) { ?>
		<div class="wp-backup-plus-method-settings" data-method="<?php esc_attr_e($backup_key); ?>">
			<?php do_action("wp_backup_plus_method_settings_{$backup_key}"); ?>
		</div>
		<?php } ?>

		<p class="submit">
			<?php wp_nonce_field('save-wp-backup-plus-settings', 'save-wp-backup-plus-settings-nonce'); ?>
			<input type="submit" class="button button-primary" name="save-wp-backup-plus-settings" value="<?php _e('Save Changes'); ?>" />
		</p>
	</form>
</div>

<a name="wp-backup-plus-compatibility"></a>
<div class="wrap" id="wp-backup-plus-compatibility">
	<h3><?php _e('Compatibility'); ?></h3>
	<p><?php _e('If there are any errors, your backup will not be performed. Please ensure that no errors are indicated below before depending on WP Backup Plus.'); ?></p>

	<table class="form-table wp-backup-plus-compatibility-table">
		<tbody>
			<?php do_action('wp_backup_plus_compatibility_table'); ?>
			<?php foreach($settings['methods'] as $method_key) { do_action("wp_backup_plus_compatibility_table_{$method_key}"); } ?>
		</tbody>
	</table>
</div>