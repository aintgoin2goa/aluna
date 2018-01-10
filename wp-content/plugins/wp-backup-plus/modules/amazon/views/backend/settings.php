<h3><?php _e('Amazon Settings'); ?></h3>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="wp-backup-plus-provider-amazon-access-key-id"><?php _e('Access Key ID'); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="wp-backup-plus-provider-amazon[access-key-id]" id="wp-backup-plus-provider-amazon-access-key-id" value="<?php esc_attr_e($settings['access-key-id']); ?>" />
				<small><a class="wp-backup-plus-help" title="<?php _e('Your Amazon Credentials'); ?>" data-content="<?php _e('In order to use Amazon S3 for backup you must <a href=\'https://aws-portal.amazon.com/gp/aws/developer/account/index.html?action=access-key#access_credentials\' target=\'_blank\'>login to the Amazon AWS console</a> and copy your access credentials to the two fields you see here. You must have Amazon S3 enabled on your account.'); ?>" href="#"><?php _e('Where can I find this information?'); ?></a></small>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="wp-backup-plus-provider-amazon-secret-access-key"><?php _e('Secret Access Key'); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="wp-backup-plus-provider-amazon[secret-access-key]" id="wp-backup-plus-provider-amazon-secret-access-key" value="<?php esc_attr_e($settings['secret-access-key']); ?>" />
			</td>
		</tr>
		
		<?php if(!is_wp_error($buckets)) { ?>
		<?php if(!empty($buckets)) { ?>
		<tr valign="top" data-amazon-bucket-action="existing">
			<th scope="row"><label for="wp-backup-plus-provider-amazon-bucket"><?php _e('Bucket Name'); ?></label></th>
			<td>
				<select class="code" name="wp-backup-plus-provider-amazon[bucket]" id="wp-backup-plus-provider-amazon-bucket">
					<?php foreach((array)$buckets as $bucket) { ?>
					<option <?php selected($settings['bucket'], $bucket->Name); ?> value="<?php esc_attr_e($bucket->Name); ?>"><?php esc_html_e($bucket->Name); ?></option>	
					<?php } ?>
				</select><br />
				
				<small><?php _e('<a class="wp-backup-plus-provider-amazon-bucket-toggle" href="#">Create a new bucket</a>'); ?></small>
			</td>
		</tr>
		<?php } ?>
		<tr valign="top" data-amazon-bucket-action="create">
			<th scope="row"><label for="wp-backup-plus-provider-amazon-bucket-name"><?php _e('Bucket Name'); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="wp-backup-plus-provider-amazon[bucket-name]" id="wp-backup-plus-provider-amazon-bucket-name" value="" />
				<small><a class="wp-backup-plus-help" title="<?php _e('Valid Bucket Names'); ?>" data-content="<?php _e('Valid bucket names are non-empty, consist only of letters, numbers, periods, hyphens and underscores, start with a letter or number and are between 3 and 255 characters long.'); ?>" href="#"><?php _e('What is a valid bucket name?'); ?></a></small>
				
				<?php if(!is_wp_error($buckets) && !empty($buckets)) { ?>
				<br />
				<small><?php _e('<a class="wp-backup-plus-provider-amazon-bucket-toggle" href="#">Select an existing bucket</a>'); ?></small>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
	</tbody>
</table>

<input type="hidden" name="wp-backup-plus-provider-amazon[bucket-action]" id="wp-backup-plus-provider-amazon-bucket-action" value="<?php echo (empty($buckets) ? 'create' : 'existing'); ?>" />