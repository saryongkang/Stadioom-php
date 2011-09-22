<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
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
		<div class="container">
                    <footer>
                        <p>&copy;<?php echo $site_name ?> by <?php echo $company_name ?> 2011</p>
                    </footer>
		</div>
		
	</section>

</body>
</html>