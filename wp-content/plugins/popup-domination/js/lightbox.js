;(function($){
	/* Stop execution if cookies are not enabled */
	var cookieEnabled=(navigator.cookieEnabled)? true : false;
	//if not IE4+ nor NS6+
	if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){ 
		document.cookie="testcookie";
		cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
	}
	if (!cookieEnabled){
		console.log('Cookies are disabled. Exiting operation.');
		return false;
	}
  enable_link_select('popup-domination-link');

  
  // the following variables detect whether or not to show the popup //
  //
  //  isMobile    => true when the useragent is in the mobiles list
  //  isHidden    => true when the popup is part of an A/B campaign that's been seen already
  //  isRefBlock  => true when the URL of the page has teh query string pdref=1 in it.
  
  var isMobile = false;
  var mobiles = ['2.0 MMP','240x320','400X240','Android','AvantGo','BlackBerry','BlackBerry9530','Blazer','Cellphone','Danger','DoCoMo',
							'Elaine/3.0','EudoraWeb','Googlebot-Mobile','hiptop','IEMobile','iPhone','iPod','KYOCERA/WX310K','LGE VX','LG/U990',
							'LG-TU915 Obigo','MIDP-2.','MMEF20','MOT-V','NetFront','Newt','Nintendo Wii','Nitro','Nokia','Nokia5800','Opera Mini',
							'Palm','PlayStation Portable','portalmmm','Proxinet','ProxiNet','SHARP-TQ-GX10','SHG-i900','Small','SonyEricsson',
							'Symbian OS','SymbianOS','TS21i-10','UP.Browser','UP.Link','webOS','Windows CE','WinWAP','YahooSeeker/M1A1-R2D2', 'iPad'];
  $.each(mobiles,function(index, value) { 
    if (navigator.userAgent.toLowerCase().indexOf(value.toLowerCase()) >= 0)
    {
      isMobile = true;
    }
  });
  var isHidden = (get_cookie('popup_domination_hide_ab'+popup_domination.campaign) == 'Y');
  var isRefBlock = (location.search.indexOf('pdref=1') > -1);
	if(typeof popup_domination == 'undefined'){
		popup_domination = '';
		return false;
	}
	var timer, exit_shown = false;
	$(document).ready(function(){
		var cururl = window.location;
		if(decodeURIComponent(popup_domination.conversionpage) == cururl){
			var abcookie = get_cookie("popup_dom_split_show");
			var camp = popup_domination.campaign;
  				if(abcookie == 'Y'){
  				var popupid = get_cookie("popup_domination_lightbox");
  					var data = {
  						action: 'popup_domination_ab_split',
  						stage: 'opt-in',
  						camp : camp,
  						popupid : popupid,
						optin: '1'
  					};
  					jQuery.post(popup_domination_admin_ajax, data, function(response) {	
  						document.cookie = 'popup_dom_split_show' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
					});
  				}
		}

		if (popup_domination.show_anim != "inpost") $(document).find('body').prepend(popup_domination.output);
		if(popup_domination.impression_count > 1){
			if(check_impressions()){
				return false;
			}
		}
		if (!isHidden && !isRefBlock){
  		switch(popup_domination.show_opt){
  			case 'mouseleave':
  				$('html,body').mouseout(window_mouseout);
  				break;
  			case 'unload':
  				enable_unload();
  				break;
  			case 'linkclick':
  				if (!popup_domination.linkclick){
  					popup_domination.linkclick = 'popup-domination-link';
  				}
  				enable_link_select(popup_domination.linkclick);
  				break;
  			default:
  				if(popup_domination.delay && popup_domination.delay > 0){
  					timer = setTimeout(show_lightbox,(popup_domination.delay*1000));
  				} else {
  					show_lightbox();
  				}
  				break;
  		}
		}

		if(popup_domination.center && popup_domination.center == 'Y')
			init_center();
		
		
		$('#popup_domination_lightbox_wrapper #close-button').click(function(){
		  if (popup_domination.close_option != 'false'){
  			close_box(popup_domination.popupid, false);
  			return false;
			}
		});
		
		
		if (popup_domination.close_option == 'false'){
			$('#popup_domination_lightbox_close').hide();
		} else {
			$('#popup_domination_lightbox_wrapper .lightbox-overlay, #popup_domination_lightbox_wrapper #popup_domination_lightbox_close').click(function(){
				close_box(popup_domination.popupid, false);
				return false;
			});
		}
		
		var provider = $('.lightbox-signup-panel .provider').val();
		
		
		// TODO REVIEW
		if(provider == 'aw'){
			$('#popup_domination_lightbox_wrapper .form div').append('</form>');
		};
		
		
		
		
		
		// method for dealing with opt-ins
		// change to .submit to avoid pop up blockers
		$('#popup_domination_lightbox_wrapper input[type="submit"],#popdom-inline-container input[type=submit]').live('click', function(e){
			e.preventDefault();
			var checked = false;
			$('#popup_domination_lightbox_wrapper :text').each(function(){
				var $this = $(this), val = $this.val();
				if($this.data('default_value') && val == $this.data('default_value')){
					if(checked)
						$this.val('').focus();
					checked = false;
				}
				if(val == ''){
					checked = false;
				}else{
					if(val == $this.data('default_value')){
						checked = false;
					}else{
						checked = true;
					}
				}
			});
			var email = $('#popup_domination_lightbox_wrapper .email').val();
			if (typeof email=="undefined"){
				register_optin(provider);
			} else if(checked){
				var name = $('.lightbox-signup-panel .name').val();
				var custom1 = $('.lightbox-signup-panel .custom1_input').val();
				var custom2 = $('.lightbox-signup-panel .custom2_input').val();
				var customf2 = $('.lightbox-signup-panel .custom_id2').val();
				var customf1 = $('.lightbox-signup-panel .custom_id1').val();
				var listid = $('.lightbox-signup-panel .listid').val();
				var mailingid = $('.lightbox-signup-panel .mailingid').val();
				var mailnotify = $('.lightbox-signup-panel .mailnotify').val();
				var campaignid = $('.lightbox-signup-panel .campaignid').val();
				var campname = $('.lightbox-signup-panel .campname').val();

				$('#popup_domination_lightbox_wrapper input[type="submit"]').attr('disabled', 'disabled');
				$('#popup_domination_lightbox_wrapper .form input').fadeOut();
				$('#popup_domination_lightbox_wrapper .wait').fadeIn();
				var data = '';
				
				if (provider != 'form' && provider != 'aw' && provider != 'nm') {
					
					data = {
						action: 'popup_domination_lightbox_submit',
						provider: provider,
						listid: listid,
						mailingid: mailingid,
						mailnotify: mailnotify,
						campaignid: campaignid,
						campname: campname,
						name: name,
						email: email,
						custom1: custom1,
						custom2: custom2,
						customf1: customf1,
						customf2: customf2
					};
					jQuery.post(popup_domination_admin_ajax, data, function(response) {
						if(response.length > 4){
							$('#popup_domination_lightbox_wrapper input[type="submit"]').removeAttr('disabled', 'disabled');
							$('#popup_domination_lightbox_wrapper .form input').fadeIn();
							$('#popup_domination_lightbox_wrapper .wait').fadeOut();
						}else{
							register_optin(provider);
						}
					}).error(function(){ // error submitting to email provider
		  				register_optin(provider);
	  				});
				} else if (provider == 'form' || provider == 'aw' || provider == 'nm') {
					register_optin(provider);
				}
			}else{
				$('popup_domination_lightbox_wrapper form').submit(function(e){
					e.preventDefault();
				});
	  			return false;
			}
			return false;
		});
		
		
		
		$('#popup_domination_lightbox_wrapper .sb_facebook').click(function(){
			if($(this).hasClass('got_user') == true){
				var email = $('.lightbox-signup-panel .fbemail').val();
				var name = $('.lightbox-signup-panel .fbname').val();
				var custom1 = $('.lightbox-signup-panel .custom1_input').val();
				var custom2 = $('.lightbox-signup-panel .custom2_input').val();
				var customf2 = $('.lightbox-signup-panel .custom_id2').val();
				var customf1 = $('.lightbox-signup-panel .custom_id1').val();
				var listid = $('.lightbox-signup-panel .listid').val();
				$('#popup_domination_lightbox_wrapper input[type="submit"]').attr('disabled', 'disabled');
				$('#popup_domination_lightbox_wrapper .form input').fadeOut();
				$('#popup_domination_lightbox_wrapper .wait').fadeIn();
				if(provider != 'form' && provider != 'aw' && provider != 'nm'){
					var data = {
						action: 'popup_domination_lightbox_submit',
						name: name,
						email: email,
						custom1: custom1,
						custom2: custom2,
						customf1: customf1,
						customf2: customf2,
						provider: provider,
						listid: listid
					};
					
					jQuery.post(popup_domination_admin_ajax, data, function(response) {
						if(response.length > 4){
							$('#popup_domination_lightbox_wrapper input[type="submit"]').removeAttr('disabled', 'disabled');
							$('#popup_domination_lightbox_wrapper .form input').fadeIn();
							$('#popup_domination_lightbox_wrapper .wait').fadeOut();
						}else{
							close_box(popup_domination.popupid);
							if(check_split_cookie() != true){
								var popupid = popup_domination.popupid;
								var data = {
				  						action: 'popup_domination_analytics_add',
				  						stage: 'opt-in',
				  						popupid: popup_domination.popupid
				  					};
				  				jQuery.post(popup_domination_admin_ajax, data, function(){
				  					redirect(popup_domination.redirect, provider);
				  				});
			  				}else{
			  					redirect(popup_domination.redirect, provider);
			  				}
							
						}
					});
				}else{
					$('#popup_domination_lightbox_wrapper .email').val(email);
					$('#popup_domination_lightbox_wrapper .name').val(name);
					if(check_split_cookie() != true){
						var popupid = popup_domination.popupid;
						var data = {
		  						action: 'popup_domination_analytics_add',
		  						stage: 'opt-in',
		  						popupid: popup_domination.popupid
		  					};
		  				jQuery.post(popup_domination_admin_ajax, data, function(){
		  					$('.lightbox-signup-panel form').submit();
		  					close_box(popup_domination.popupid);
		  				});
		  				return false;
		  			}else{
		  				$('.lightbox-signup-panel form').submit();
		  				close_box(popup_domination.popupid);
		  			}
		  			return false;
				}
				return false;
			}
		});
		
		
		$(function () {
		    var ele = $(".lightbox-download-nums");
		    var clr = null;
		    var number = $(".lightbox-download-nums").text();
		    number = parseInt(number);
		    var rand = number;
		    loop();
		    function loop() {
		        clearInterval(clr);
		        inloop();
		        setTimeout(loop, 1000);
		    }
		    function inloop() {
		        ele.html(rand += 1);
		        if (!(rand % 50)) {
		            return;
		        }
		        clr = setTimeout(inloop, 3000);
		    }
		});
	});
	
	
	function redirect(page, provider) {
		if (page != '' && provider != 'form') {
			window.location.href = decodeURIComponent(page);
		}
	}
	
	function social_submit(){
		if($('#popup_domination_lightbox_wrapper .fbemail').val() != 'none' && $('#popup_domination_lightbox_wrapper .fbemail').val() != 'none'){
			var checked = false;
			$('#popup_domination_lightbox_wrapper :text').each(function(){
				var $this = $(this), val = $this.val();
				if($this.data('default_value') && val == $this.data('default_value')){
					if(checked)
						$this.val('').focus();
					checked = false;
				}
				if(val == ''){
					checked = false;
				}else{
					if(val == $this.data('default_value')){
						checked = false;
					}else{
						checked = true;
					}
				}
			});
			if(checked){
				var email = $('.lightbox-signup-panel .fbemail').val();
				var name = $('.lightbox-signup-panel .fbname').val();
				var listid = $('.lightbox-signup-panel .listid').val();
				var provider = $('.lightbox-signup-panel .provider').val();
				$('#popup_domination_lightbox_wrapper input[type="submit"]').attr('disabled', 'disabled');
				$('#popup_domination_lightbox_wrapper .form input').fadeOut();
				$('#popup_domination_lightbox_wrapper .wait').fadeIn();
				if(provider != 'form' && provider != 'aw' && provider != 'nm'){
					var data = {
						action: 'popup_domination_lightbox_submit',
						name: name,
						email: email,
						provider: provider,
						listid: listid
					};
					jQuery.post(popup_domination_admin_ajax, data, function(response) {
						if(response.length > 4){
							$('#popup_domination_lightbox_wrapper input[type="submit"]').removeAttr('disabled', 'disabled');
							$('#popup_domination_lightbox_wrapper .form input').fadeIn();
							$('#popup_domination_lightbox_wrapper .wait').fadeOut();
						}else{
							close_box(popup_domination.popupid);
							if(check_split_cookie() != true){
								var popupid = popup_domination.popupid;
								var data = {
				  						action: 'popup_domination_analytics_add',
				  						stage: 'opt-in',
				  						popupid: popup_domination.popupid
				  					};
				  				jQuery.post(popup_domination_admin_ajax, data, function(){
				  					redirect(popup_domination.redirect, provider);
				  				});
			  				}else{
			  					redirect(popup_domination.redirect, provider);
			  				}
						}
					});
				}else{
					if(check_split_cookie() != true){
						var popupid = popup_domination.popupid;
						var data = {
		  						action: 'popup_domination_analytics_add',
		  						stage: 'opt-in',
		  						popupid: popup_domination.popupid
		  					};
		  				jQuery.post(popup_domination_admin_ajax, data, function(){
		  					$('.lightbox-signup-panel form').submit();
		  					close_box(popup_domination.popupid);
		  				});
		  				return false;
		  			}else{
		  				$('.lightbox-signup-panel form').submit();
		  				close_box(popup_domination.popupid);
		  			}
		  			return false;
				}
			}
			return false;
		}
	}
	
	function register_view(){
		var data = '';
		if(check_split_cookie() != true){				
			data = {
				action: 'popup_domination_analytics_add',
				stage: 'show',
				popupid: popup_domination.popupid
			};
		}else{
			var date = new Date();
			date.setTime(date.getTime() + (86400*1000));
			set_cookie('popup_dom_split_show','Y', date);
			set_cookie('popup_domination_lightbox',popup_domination.popupid,date);
			data = {
				action: 'popup_domination_ab_split',
				stage: 'show',
				popupid: popup_domination.popupid,
				camp : popup_domination.campaign
			};
			
		}
		jQuery.post(popup_domination_admin_ajax, data);
	}
	
	function register_optin(prov){
		var data = '';
		if(check_split_cookie() != true){
			data = {
				action: 'popup_domination_analytics_add',
				stage: 'opt-in',
				popupid: popup_domination.popupid
			};
		}else{
			data = {
				action: 'popup_domination_ab_split',
				stage: 'opt-in',
				popupid: popup_domination.popupid,
				camp : popup_domination.campaign
			};
		}
		
		//submit depending on provider
		if (prov == 'form' || prov == 'aw' || prov == 'nm' || typeof prov == "undefined") {
			jQuery.post(popup_domination_admin_ajax, data, function(){
				$('#popup_domination_lightbox_wrapper form').submit();
				close_box(popup_domination.popupid, true);

			}).error(function(){
				$('#popup_domination_lightbox_wrapper form').submit();
				close_box(popup_domination.popupid, true);

			});
		} else {
			jQuery.post(popup_domination_admin_ajax, data, function(){
				close_box(popup_domination.popupid, true);
				redirect(popup_domination.redirect, provider);
			}).error(function(){
				close_box(popup_domination.popupid, false);
				redirect(popup_domination.redirect, provider);
			});
		}
	}
	
	function enable_unload(){
		window.onbeforeunload = function(e){ 
			if(exit_shown === false){
				e = e || window.event;
				exit_shown = true;
				setTimeout(show_lightbox,1000);
				if(e)
					e.returnValue = popup_domination.unload_msg;
				return popup_domination.unload_msg; 
			}
		};
	};
	function enable_link_select(classname){
    $('.'+classname).click(function(e){
      e.preventDefault();
      show_lightbox(true);
    });
  }
	function window_mouseout(e){
		var scrollTop = jQuery(window).scrollTop()+5;
        var scrollBottom = jQuery(window).scrollTop() + jQuery(window).height()-5;
        var scrollLeft = jQuery(window).scrollLeft()+5;
        var scrollRight = scrollLeft + jQuery(window).width()-5;
        var mX = e.pageX, mY = e.pageY, el = $(window).find('html');
        
        if ((mX <= scrollLeft) || (mX >= scrollRight) || (mY <= scrollTop) || (mY>= scrollBottom)) {
	        show_lightbox();
        }
	};

  function show_lightbox(linkclick){
    if (!isMobile && !isHidden && !isRefBlock && popup_domination.show_anim != "inpost")
    {
      $(document).unbind('focus',show_lightbox);
      $('html,body').unbind('mouseout',window_mouseout);
      if(!check_cookie(popup_domination.popupid) || linkclick){
        max_zindex();
        //$('#popup_domination_lightbox_wrapper').fadeIn('fast');
        show_animation();
        if(popup_domination.center && popup_domination.center == 'Y'){
          center_it();
        }
        register_view();
      }
      provider = $('.lightbox-signup-panel .provider').val();
      if(provider == 'aw'){
        var html = $('#popup_domination_lightbox_wrapper .form div').html();
        if($('#popup_domination_lightbox_wrapper .form form').html() == null){
          $('#popup_domination_lightbox_wrapper .form div').html('<form method="post" action="http://www.aweber.com/scripts/addlead.pl">'+html+'</form>')
          //$('#popup_domination_lightbox_wrapper .form div form').prepend(html);
        }else {
          $('#popup_domination_lightbox_wrapper .form form').remove();
          $('#popup_domination_lightbox_wrapper .form div').html('<form method="post" action="http://www.aweber.com/scripts/addlead.pl">'+html+'</form>')

          //$('#popup_domination_lightbox_wrapper .form div form').prepend('</form>');
        }
      }
    }
    if (isMobile)
    {
      //email = prompt($('#popup_domination_lightbox_wrapper input[type="submit"]').val()+"\nEnter your email");
    }
    return false;
	};
	
	/* decides how lightbox is to show */
	function show_animation(){
	  $('#popup_domination_lightbox_wrapper>.lightbox-main').css("opacity",0);
	  $('#popup_domination_lightbox_wrapper').show();
    if (popup_domination.show_anim == 'fade') {
      $('#popup_domination_lightbox_wrapper>.lightbox-main').animate({opacity:1},500,center_it);
    } else if (popup_domination.show_anim == 'slide') {
      $('#popup_domination_lightbox_wrapper>.lightbox-main').animate({top: 0 - $('#popup_domination_lightbox_wrapper>.lightbox-main').outerHeight()},0);
      $('#popup_domination_lightbox_wrapper>.lightbox-main').animate({
        opacity: 1,
        top: ($(window).height() - $('.popup-dom-lightbox-wrapper .lightbox-main').outerHeight())/2
        }, 
        500, 
        center_it);
    } else {
      $('#popup_domination_lightbox_wrapper>.lightbox-main').css("opacity",1);
    }
    
	}
	
	function center_it(){
		var styles = {
			position:'fixed',
			left: ($(window).width() - $('.popup-dom-lightbox-wrapper .lightbox-main').outerWidth())/2,
			top: ($(window).height() - $('.popup-dom-lightbox-wrapper .lightbox-main').outerHeight())/2
		};
		styles.left = styles.left < 0 ? 0 : styles.left;
		styles.top = styles.top < 0 ? 0 : styles.top;
		$('.popup-dom-lightbox-wrapper .lightbox-main').css(styles);
	};
	function init_center(){
		center_it();
		$(window).resize(center_it);
	};
	function max_zindex(){
		var maxz = 0;
		$('body *').each(function(){
			var cur = parseInt($(this).css('z-index'));
			maxz = cur > maxz ? cur : maxz;
		});
		$('#popup_domination_lightbox_wrapper').css('z-index',maxz+10);
	};
	
	function hide_box(){
  	$('#popup_domination_lightbox_wrappe').hide();
	}
	
	function close_box(id, success){
		var elem = $('#popup_domination_lightbox_wrapper');
		clearTimeout(timer);
			elem.fadeOut('fast');
			if(popup_domination.cookie_time && popup_domination.cookie_time > 0){
				var date = new Date();
				if (success == true){ // set to a high number so those who have already opted in do not see the pop up again
					date.setTime(date.getTime() + (1000*86400*1000));
				} else {
					date.setTime(date.getTime() + (popup_domination.cookie_time*86400*1000));
				}
				if(id == '0'){
					id = 'zero';
				}else if(id == '1'){
					id = 'one';
				}else if(id == '3'){
					id = 'three';
				}else if(id == '4'){
					id = 'four';
				}
				set_cookie('popup_domination_hide_lightbox'+id,'Y',date);
				stop_video();
				if (check_split_cookie()) {
				  set_cookie('popup_domination_hide_ab'+popup_domination.campaign,'Y',date);
				}
			}
	};
	function stop_video(){
		//Required for some plugins such as Vimeo
		$('#popup_domination_lightbox_wrapper .lightbox-video iframe').remove();
	};
	function set_cookie(name,value,date){
		window.document.cookie = [name+'='+escape(value),'expires='+date.toUTCString(),'path='+popup_domination.cookie_path].join('; ');
	};
	function check_cookie(id){
		if(id == '0'){
			id = 'zero';
		}else if(id == '1'){
			id = 'one';
		}else if(id == '3'){
			id = 'three';
		}else if(id == '4'){
			id = 'four';
		}
		if(get_cookie('popup_domination_hide_lightbox'+id) == 'Y')
			return true;
		return false;
	};
	function check_split_cookie(){
		return popup_domination.splitcookie;
	}
	function check_impressions(){
		var ic = 1, date = new Date();
		if(ic = get_cookie('popup_domination_icount')){
			ic = parseInt(ic);
			ic++;
			if(ic == popup_domination.impression_count){
				date.setTime(date.getTime());
				set_cookie('popup_domination_icount',popup_domination.impression_count,date);
				return false;
			}
		} else {
			ic = 1;
		}
		date.setTime(date.getTime() + (7200*1000));
		set_cookie('popup_domination_icount',ic,date);
		return true;
	};
	
	function get_cookie(cname){
		var cookie = window.document.cookie;
		if(cookie.length > 0){
			var c_start = cookie.indexOf(cname+'=');
			if(c_start !== -1){
				c_start = c_start + cname.length+1;
				var c_end = cookie.indexOf(';',c_start);
				if(c_end === -1)
					c_end = cookie.length;
				return unescape(cookie.substring(c_start,c_end));
			}
		}
		return false;
	};
})(jQuery);