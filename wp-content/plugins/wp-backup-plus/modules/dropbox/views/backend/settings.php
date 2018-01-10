<h3><?php _e('Dropbox Settings'); ?></h3>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="wp-backup-plus-provider-dropbox-app-key"><?php _e('App Key'); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="wp-backup-plus-provider-dropbox[app-key]" id="wp-backup-plus-provider-dropbox-dropbox-app-key" value="<?php esc_attr_e($settings['app-key']); ?>" />
				<small><a class="wp-backup-plus-help" title="<?php _e('Your Dropbox App'); ?>" data-content="<?php _e('In order to use Dropbox for backup you must <a href=\'https://www.dropbox.com/account#applications\'>register a Dropbox application</a> and add the keys you receive in the two fields you see here.'); ?>" href="#"><?php _e('Where can I find this information?'); ?></a></small>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="wp-backup-plus-provider-dropbox-app-secret"><?php _e('App Secret'); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="wp-backup-plus-provider-dropbox[app-secret]" id="wp-backup-plus-provider-dropbox-app-secret" value="<?php esc_attr_e($settings['app-secret']); ?>" /><br />
			</td>
		</tr>
		
		<?php if(!empty($authorize_url) || !empty($settings['access-token'])) { ?>
		<tr valign="top">
			<th scope="row"><label for="wp-backup-plus-provider-dropbox-access-token"><?php _e('Get Access Token'); ?></label></th>
			<td>
				<?php if(!empty($authorize_url)) { ?>
					<?php if(is_wp_error($authorize_url)) { ?>
					<?php _e('App Key and App Secret failed validation. Please correct your credentials and save the changes.'); ?>	
					<?php } else { ?>
					<a href="<?php esc_attr_e($authorize_url); ?>"><?php _e('Get Access Token'); ?></a>
					<?php } ?>
				<?php } else { ?>
					<?php foreach($settings['access-token'] as $access_token_key => $access_token_value) { ?>
					<input type="hidden" name="wp-backup-plus-provider-dropbox[access-token][<?php esc_attr_e($access_token_key); ?>]" value="<?php esc_attr_e($access_token_value); ?>" />	
					<?php } ?>
					<?php _e('You have successfully retrieved your access token.'); ?>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>