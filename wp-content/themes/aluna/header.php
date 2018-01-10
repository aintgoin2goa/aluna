<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php bloginfo('name'); ?> <?php wp_title('|'); ?></title>
<link rel="shortcut icon" href="<?php url(); ?>/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<script type="text/javascript" src="<?php url(); ?>/js/modernizr.js"></script>
<script type="text/javascript" src="<?php url(); ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php url(); ?>/js/html5.js"></script>
<script type="text/javascript" src="<?php url(); ?>/js/slideshow.js"></script>
<!--[if IE 6]>
	<style type="text/css">
		#logo img, #strap{ behavior: url(<?php url(); ?>/iepngfix.htc) }
        #languages{ background-color: #bccacf; }
        #languages h4{ 	background:url(<?php url();?>/images/languages_ie.png) left top no-repeat; width:1px; }
        #languages a{ background-image:url(<?php url() ;?>/images/flags_ie.png);
        #nav li{ margin-left:-3px;}
         

	</style>
<![endif]-->

<?php wp_head(); ?>

</head>

<body class=<?php the_ID(); ?>>
