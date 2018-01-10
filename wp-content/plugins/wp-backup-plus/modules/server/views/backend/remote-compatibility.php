<tr valign="top">
	<th scope="row" colspan="2"><strong><?php _e('Remote Server Compatibility'); ?></strong></th>
</tr>


<tr valign="top">
	<th scope="row"><?php _e('FTP Extension Loaded?'); ?></th>
	<td>
		<?php if($ftp_extension_exists) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the PHP <code>ftp_connect</code> function is available and the PHP FTP extension is loaded.')); ?>
		<?php } ?>
	</td>
</tr>


<?php if($ftp_extension_exists) { ?>
	<tr valign="top">
		<th scope="row"><?php _e('FTP Credentials Correct?'); ?></th>
		<td>
			<?php if($ftp_credentials_correct) { ?>
			<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
			<?php } else { ?>
			<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the credentials you entered above are correct.')); ?>
			<?php } ?>
		</td>
	</tr>


	<?php if($ftp_credentials_correct) { ?>
	<tr valign="top">
		<th scope="row"><?php _e('Path Valid?'); ?></th>
		<td>
			<?php if($path_valid) { ?>
			<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
			<?php } else { ?>
			<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure the path you entered above exists and is accessible to the user you specified.'), $temporary_directory); ?>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	

<?php } ?>