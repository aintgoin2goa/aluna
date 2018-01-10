jQuery(document).ready(function($) {
	var $status_field = $('#wp-backup-plus-backup-status-field')
	, $status_field_container = $('#wp-backup-plus-backup-status');

	if($status_field.size() > 0) {
		setInterval(function() {
			$.get(
				ajaxurl,
				{ action: 'wp_backup_plus_progress' },
				function(data, status) {
					if(data.in_progress) {
						$status_field_container.show();

					}

					if(data.messages.length > 0) {
						var new_messages = data.messages.slice(parseInt($status_field.attr('data-last-message-index')));
						if(new_messages.length > 0) {
							var message_text = $status_field.val() + new_messages.join("\n") + "\n";
							$status_field.val(message_text).attr('data-last-message-index', data.messages.length).scrollTop($status_field.get(0).scrollHeight);
						}
					}
				},
				'json'
			);
		}, 5000);
	}

	$('input.wp-backup-plus-method').bind('click change', function(event) {
		var $dependents = $('.wp-backup-plus-method-settings').hide();
		var $checked_methods = $('input.wp-backup-plus-method:checked').each(function(index, element) {
			$dependents.filter('[data-method="' + $(element).attr('value') + '"]').show();
		});
	}).filter(':first').change();

	$('.wp-backup-plus-help').each(function(index, element) {
		var $link = $(element);

		$link.pointer({
			content: '<h3>' + $link.attr('title') + '</h3><p>' + $link.attr('data-content') + '</p>',
			position: 'top'
		}).click(function(event) {
			event.preventDefault();

			$link.pointer('toggle');
		});
	});

	$('#wp-backup-plus-notification').bind('click change', function(event) {
		var $this = $(this);
		var $dependency = $('#wp-backup-plus-email').parents('tr');

		if($this.is(':checked')) {
			$dependency.show();
		} else {
			$dependency.hide();
		}
	}).change();

	$('.wp-backup-plus-backup-toggle').click(function(event) {
		event.preventDefault();

		var $backup_action = $('#wp-backup-plus-backup-action');
		var backup_action = $backup_action.val() == 'upload' ? 'existing' : 'upload';
		$backup_action.val(backup_action).change();
	});

	$('#wp-backup-plus-backup-action').change(function(event) {
		var backup_action = $(this).val() == 'upload' ? 'upload' : 'existing';

		$('[data-backup-action]').hide().filter('[data-backup-action="' + backup_action + '"]').show();

		$download = $('#wp-backup-plus-download-backup');
		if('upload' == backup_action) {
			$download.hide();
		} else {
			$download.show();
		}
	}).change();
});
