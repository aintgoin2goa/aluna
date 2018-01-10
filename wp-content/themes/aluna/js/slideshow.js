// JavaScript Document


jQuery.fn.slideshow = function(){
	
	var current = 0;
	var imgs = $(this).children('img');
	var max = imgs.length - 1;
	
	window.setInterval( function(){
		var n = current + 1;							 
		if(n > max) n = 0;
		$(imgs[current]).fadeOut(1500);
		$(imgs[n]).fadeIn(1500);
		current = n;
	}, 5000);
	
	
	
}


$(window).load( function(){
	$('#slideshow').slideshow();						 
});