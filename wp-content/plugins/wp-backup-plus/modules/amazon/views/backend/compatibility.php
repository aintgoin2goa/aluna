<tr valign="top">
	<th scope="row" colspan="2"><strong><?php _e('Amazon S3 Compatibility'); ?></strong></th>
</tr>

<tr valign="top">
	<th scope="row"><?php _e('Credentials Entered?'); ?></th>
	<td>
		<?php if($has_credentials) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please enter your Access Key ID and Secret Access Key in the appropriate fields above.')); ?>
		<?php } ?>
	</td>
</tr>

<?php if($has_credentials) { ?>
<tr valign="top">
	<th scope="row"><?php _e('Credentials Correct?'); ?></th>
	<td>
		<?php if($credentials_valid) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the Access Key ID and Secret Access Key you entered are correct.')); ?>
		<?php } ?>
	</td>
</tr>

	<?php if($credentials_valid) { ?>
		<tr valign="top">
			<th scope="row"><?php _e('Bucket Selected?'); ?></th>
			<td>
				<?php if($has_bucket) { ?>
				<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
				<?php } else { ?>
				<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php _e('Please select the bucket you wish your backups to be inserted into and then save your changes.'); ?>
				<?php } ?>
			</td>
		</tr>
		
		<?php if($has_bucket) { ?>
		<tr valign="top">
			<th scope="row"><?php _e('Bucket is Valid?'); ?></th>
			<td>
				<?php if($bucket_valid) { ?>
				<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
				<?php } else { ?>
				<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php _e('You previously selected a bucket, but it appears to no longer be valid. Please select a valid bucket above and then save your changes.'); ?>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
	
	<?php } ?>
<?php } ?>