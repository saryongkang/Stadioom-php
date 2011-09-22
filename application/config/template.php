<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$template_conf = array(
	'template' => 'default',
	'site_name' => 'Stadioom',
        'company_name'=> 'SeedShock',
	'site_title' => 'Where everyone is playing at',
	'devmode' => false,
	'content' => '',
	'css' => '',
	'js' => '',
	'head' => '',
	'messages' => '',
	'assets_dir' => 'assets/'
);

$template_css = array('base');

$template_js = array();

$template_head = array(
	'jquery' => '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
					<script type="text/javascript">
					google.load("jquery", "1.6.0");
					</script>',
    
        'google-analytics'  => "<!-- Google Analytics -->
      <script type='text/javascript'>
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-25850852-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
      </script>"
);
