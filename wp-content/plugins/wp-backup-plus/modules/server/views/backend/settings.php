<h3><?php _e('Server Settings'); ?></h3>

<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="wp-backup-plus-provider-server-type"><?php _e('Type'); ?></label></th>
		<td>
			<ul class="wp-backup-plus-provider-server-no-margin">
				<li>
					<label>
						<input <?php checked($settings['type'], 'local'); ?> class="wp-backup-plus-provider-server-type" type="radio" name="wp-backup-plus-provider-server[type]" id="wp-backup-plus-provider-server-type-local" value="local" />
						<?php _e('Local'); ?>
					</label>
				</li>
				<li>
					<label>
						<input <?php checked($settings['type'], 'remote'); ?> class="wp-backup-plus-provider-server-type" type="radio" name="wp-backup-plus-provider-server[type]" id="wp-backup-plus-provider-server-type-remote" value="remote" />
						<?php _e('Remote'); ?>
					</label>
				</li>
			</ul>
		</td>
	</tr>
	<tr valign="top" data-server-type="local">
		<th scope="row"><label for="wp-backup-plus-provider-server-path"><?php _e('Path'); ?></label></th>
		<td>
			<input type="text" class="code large-text" name="wp-backup-plus-provider-server[local-directory]" id="wp-backup-plus-provider-server-local-directory" value="<?php esc_attr_e($settings['local-directory']); ?>" /><br />
			<small><?php _e('Enter the path to the directory in which you wish to store backups. The directory should exist and be writable by the PHP process.'); ?></small>
		</td>
	</tr>
	<tr valign="top" data-server-type="remote">
		<th scope="row"><label for="wp-backup-plus-provider-server-secure"><?php _e('FTP Host'); ?></label></th>
		<td>
			<input type="text" class="code regular-text" name="wp-backup-plus-provider-server[host]" id="wp-backup-plus-provider-server-host" value="<?php esc_attr_e($settings['host']); ?>" />
		</td>
	</tr>
	<tr valign="top" data-server-type="remote">
		<th scope="row"><label for="wp-backup-plus-provider-server-username"><?php _e('FTP Username'); ?></label></th>
		<td>
			<input type="text" class="code regular-text" name="wp-backup-plus-provider-server[username]" id="wp-backup-plus-provider-server-username" value="<?php esc_attr_e($settings['username']); ?>" />
		</td>
	</tr>
	<tr valign="top" data-server-type="remote">
		<th scope="row"><label for="wp-backup-plus-provider-server-password"><?php _e('FTP Password'); ?></label></th>
		<td>
			<input type="password" class="code regular-text" name="wp-backup-plus-provider-server[password]" id="wp-backup-plus-provider-server-password" value="<?php esc_attr_e($settings['password']); ?>" />
		</td>
	</tr>
	<tr valign="top" data-server-type="remote">
		<th scope="row"><label for="wp-backup-plus-provider-server-path"><?php _e('FTP Path'); ?></label></th>
		<td>
			<input type="text" class="code large-text" name="wp-backup-plus-provider-server[remote-directory]" id="wp-backup-plus-provider-server-remote-directory" value="<?php esc_attr_e($settings['remote-directory']); ?>" /><br />
			<small><?php _e('Enter the path to the directory in which you wish to store backups. The directory should exist and be accessible to the FTP credentials you enter below.'); ?></small>
		</td>
	</tr>
</table>

<input type="hidden" name="wp-backup-plus-provider-server[secure]" id="wp-backup-plus-provider-server-secure" value="FTP" />