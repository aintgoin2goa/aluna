<tr valign="top">
	<th scope="row" colspan="2"><strong><?php _e('Dropbox Compatibility'); ?></strong></th>
</tr>

<tr valign="top">
	<th scope="row"><?php _e('Credentials Entered?'); ?></th>
	<td>
		<?php if($has_credentials) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please enter your App Key and App Secret in the appropriate fields above.')); ?>
		<?php } ?>
	</td>
</tr>

<?php if($has_credentials) { ?>
<tr valign="top">
	<th scope="row"><?php _e('Access Token Retrieved?'); ?></th>
	<td>
		<?php if($has_access_token) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please click the link above to authenticate your account with Dropbox and retrieve your access token.')); ?>
		<?php } ?>
	</td>
</tr>

	<?php if($has_access_token) { ?>
		<tr valign="top">
			<th scope="row"><?php _e('Access Token Valid?'); ?></th>
			<td>
				<?php if($access_token_valid) { ?>
				<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
				<?php } else { ?>
				<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php _e('Please click the link above to reauthenticate your account with Dropbox and retrieve a new access token.'); ?>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
<?php } ?>