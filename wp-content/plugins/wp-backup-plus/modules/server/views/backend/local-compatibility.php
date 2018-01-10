<tr valign="top">
	<th scope="row" colspan="2"><strong><?php _e('Local Server Compatibility'); ?></strong></th>
</tr>


<tr valign="top">
	<th scope="row"><?php _e('Directory Exists?'); ?></th>
	<td>
		<?php if($directory_exists) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the directory you specified exists.')); ?>
		<?php } ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Directory Writeable?'); ?></th>
	<td>
		<?php if($directory_writable) { ?>
		<strong class="wp-backup-plus-meets"><?php _e('Yes!'); ?></strong>	
		<?php } else { ?>
		<strong class="wp-backup-plus-lacks"><?php _e('No!'); ?></strong> <?php printf(__('Please ensure that the directory you specified is writable by the PHP process.')); ?>
		<?php } ?>
	</td>
</tr>