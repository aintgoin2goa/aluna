jQuery(document).ready(function($) {
	$('.wp-backup-plus-provider-amazon-bucket-toggle').click(function(event) {
		event.preventDefault();
		
		var $bucket_action = $('#wp-backup-plus-provider-amazon-bucket-action');
		var bucket_action = $bucket_action.val() == 'create' ? 'existing' : 'create';
		$bucket_action.val(bucket_action).change();
	});
	
	$('#wp-backup-plus-provider-amazon-bucket-action').change(function(event) {
		var bucket_action = $(this).val() == 'create' ? 'create' : 'existing';
		
		$('[data-amazon-bucket-action]').hide().filter('[data-amazon-bucket-action="' + bucket_action + '"]').show();
	}).change();
});
