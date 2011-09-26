<!DOCTYPE html>
<html xmlns:fb="https://www.facebook.com/2008/fbml" >
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
	<meta charset="utf-8">
    <meta property="fb:app_id"      content="200987663288876"> 
    <meta property="og:type"        content="website"> 
    <meta property="og:url"         content="http://stadioom.com"> 
    <meta property="og:title"       content="Stadioom - Connecting Sports"> 
	<title><?php echo $site_title?> - <?php echo $site_name?></title>
        <!-- fav and touch icons -->
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	<?php echo $head?>
	<?php echo $css?>
	<?php echo $js?>
</head>

<body>

	<section id="header">
            <div class="topbar">
              <div class="fill">
                <div class="container">
                  <a class="brand" href="#"><?php echo $site_name ?></a>
                  <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                  </ul>
                </div>
              </div>
            </div>
	
	</section>


	<section id="main">

		<div class="container">
		
			<div class="main">
				<?php echo $messages?>
				<?php echo $content?>
			</div>
			
		</div>

	</section>


	<section id="footer">
		<div class="container center">
                    <footer>
                        <p>&copy;<?php echo $site_name ?> by  <img class="miniseedshock-logo" src="assets/images/SeedShock_log0_mini.jpg" />  <?php echo $company_name ?> 2011</p>
                    </footer>
		</div>
		
	</section>

</body>
</html>