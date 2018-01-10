<tr valign="top">
	<th scope="row" colspan="2"><strong><?php _e('General Compatibility'); ?></strong></th>
</tr>


<tr valign="top">
	<th scope="row"><?php _e('Shell Exec Operational?'); ?></th>
	<td>
		<?php if($has_shell_exec) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that your PHP process can use the <code>shell_exec</code> command.')); ?>
		<?php } ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Temporary Directory Exists?'); ?></th>
	<td>
		<?php if($has_temporary_directory) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the WP Backup Plus temporary directory (<code>%s</code>) has been created.'), $temporary_directory); ?>
		<?php } ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Temporary Directory Writeable?'); ?></th>
	<td>
		<?php if($temporary_directory_writable) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the WP Backup Plus temporary directory (<code>%s</code>) is writable by the web server.'), $temporary_directory); ?>
		<?php } ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('ZipArchive Installed?'); ?></th>
	<td>
		<?php if($has_zip_archive) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php _e('Please install the <a href="http://www.php.net/manual/en/intro.zip.php">PHP ZipArchive</a> extension so that WP Backup Plus can archive your database and files appropriately.'); ?>
		<?php } ?>
	</td>
</tr>
