<div class="wrap">

	<form method="post" action="<?php esc_url(add_query_arg(array())); ?>">

		<?php screen_icon(); ?>
		<h2 id="wp-backup-plus-backup-and-restore-page-title"><?php _e('WP Backup Plus - Backup &amp; Restore'); ?></h2>
		<?php settings_errors(); ?>

		<h3><?php _e('On Demand Backup'); ?></h3>

		<p class="submit">
			<input type="submit" class="button button-primary" name="wp-backup-plus-backup-now" value="<?php _e('Backup Now!'); ?>" />
			<input type="submit" class="button button-secondary" name="wp-backup-plus-backup-download" value="<?php _e('Download Backup Now!'); ?>" />
		</p>

		<?php wp_nonce_field('wp-backup-plus-backup-now', 'wp-backup-plus-backup-now-nonce'); ?>
	</form>

	<div id="wp-backup-plus-backup-status">
		<h3><?php _e('Backup Status'); ?></h3>

		<p><?php _e('A backup is currently in progress. Please see below for information about the status.'); ?></p>

		<textarea rows="7" class="code large-text" id="wp-backup-plus-backup-status-field"></textarea>
	</div>

	<form enctype="multipart/form-data" method="post" action="<?php esc_url(add_query_arg(array())); ?>">

		<h3><?php _e('Download or Restore a Previous Backup'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr valign="top" data-backup-action="existing">
					<th scope="row"><label for="wp-backup-plus-backup-data"><?php _e('Select Backup'); ?></label></th>
					<td>
						<?php if(!empty($backups)) { ?>
						<select class="code" name="wp-backup-plus[backup-data]" id="wp-backup-plus-backup-data">
							<?php foreach($backups as $method_key => $method_backups) { if(empty($method_backups)) { continue; } ?>
							<optgroup label="<?php esc_attr_e(self::$backup_methods[$method_key]); ?>">
								<?php foreach($method_backups as $backup) { ?>
								<option value="<?php esc_attr_e(maybe_serialize($backup)); ?>"><?php esc_html_e($backup->WPBP); ?></option>
								<?php } ?>
							</optgroup>
							<?php } ?>
						</select><br />

						<small><?php _e('<a class="wp-backup-plus-backup-toggle" href="#">Upload a backup file</a>'); ?></small>
						<?php } ?>
					</td>
				</tr>
				<tr valign="top" data-backup-action="upload">
					<th scope="row"><label for="wp-backup-plus-backup-file"><?php _e('Select Backup'); ?></label></th>
					<td>
						<input type="file" name="wp-backup-plus-backup-file" id="wp-backup-plus-backup-file" value="" />

						<?php if(!empty($backups)) { ?>
						<br />
						<small><?php _e('<a class="wp-backup-plus-backup-toggle" href="#">Select an existing backup</a>'); ?></small>
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>

		<input type="hidden" name="wp-backup-plus[backup-action]" id="wp-backup-plus-backup-action" value="<?php echo (empty($backups) ? 'upload' : 'existing'); ?>" />

		<p class="submit">
			<?php wp_nonce_field('wp-backup-plus-download-or-restore-backup', 'wp-backup-plus-download-or-restore-backup-nonce'); ?>
			<?php  if($root_writable) { ?>
			<input type="submit" class="button button-primary" name="wp-backup-plus-restore-backup" id="wp-backup-plus-restore-backup" value="<?php _e('Restore Backup'); ?>" />
			<?php } ?>
			<input type="submit" class="button button-secondary" name="wp-backup-plus-download-backup" id="wp-backup-plus-download-backup" value="<?php _e('Download Backup'); ?>" />
		</p>
	</form>
</div>

