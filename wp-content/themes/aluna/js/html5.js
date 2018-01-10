// JavaScript Document
(function(jQuery){

	jQuery.Validate= {
	
	options: {
		errorClass : 'fieldWithErrors'
	},
	
	messages: {
		required : 'This field is required',
		email : 'This is not a valid email',
		matches : 'Value does not match',
		url : 'This is not a valid url'
		
	},
	
	required : function(val){
		return $.trim(val) == "" ? false : true;
	},
	
	email: function(val){
		var regexp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		return regexp.test(val);
	},
	
	matches: function(val, test){
		return val == test ? true : false;	
	},
	
	url: function(val){
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		return regexp.test(val);
	}
}


jQuery.fn.validate = function(){
	$(this).submit( function(e){
		e.preventDefault();
		$(this).attr('validationpassed', 'yes');
		var form = $(this);
		var val, msg;
		$(this).find('input[validation], select[validation], textarea[validation]').each( function(){
			$($(this).parent()).removeClass(jQuery.Validate.options.errorClass);		
			$($(this).parent()).find('.errorMessage').remove();
			if( $(this).attr('placeholder') == this.value ) this.value = '';
			val = this.value;
			var str = $(this).attr('validation').split(' ');
			var func = str[0];
			var arg = str.length > 1 ? str[1] : '';
			if( arg.indexOf('$') == 0 ){
				arg = arg.replace('$', '');
				arg = $(arg).attr('value');
			}
			if( jQuery.Validate[func](val, arg) === false ){
				form.attr('validationpassed', 'no');
				$($(this).parent()).addClass(jQuery.Validate.options.errorClass);
				if( $(this).attr('errorMessage') == undefined ){
					msg = jQuery.Validate.messages[func];
				}else{
					msg = 	$(this).attr('errorMessage')
				}
				$($(this).parent()).prepend('<p class="errorMessage">'+msg+'</p>');
			}
		});
		if( $(this).attr('validationpassed') == 'yes' ){
			$(this).trigger('validationPassed');	
		}
		
	});
	
}



$(document).ready( function(){
							
	$('input[placeholder]').each( function(){
		$(this).focus( function(){
			if( this.value == $(this).attr('placeholder') ) this.value = '';						
		});
		$(this).blur( function(){
			if( jQuery.trim(this.value) == '' ) this.value = $(this).attr('placeholder');						
		});
		if( jQuery.trim(this.value) == '' ){
			this.value = $(this).attr('placeholder');			
		}
	});
	$('textarea[placeholder]').each( function(){
		$(this).focus( function(){
			if( this.value == $(this).attr('placeholder') ) this.value = '';						
		});
		$(this).blur( function(){
			if( jQuery.trim(this.value) == '' ) this.value = $(this).attr('placeholder');						
		});
		if( jQuery.trim(this.value) == '' ){
			this.value = $(this).attr('placeholder');			
		}
	});
	$('form[validate]').validate();
								
							
});


}(jQuery));





