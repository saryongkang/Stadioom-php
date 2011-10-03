
<div class="container">

  <!-- Main hero unit for a primary marketing message or call to action -->

  <div class="row">
      <div class="span8">
          Logo goes here
      </div>
      <div class="span8 showgrid">
  
          <?php if ($fbUser): ?>
            <a href="<?php echo $logoutUrl; ?>">Logout</a>
            <?php else: ?>
            <div id="php-fb-connect">
            Login using OAuth 2.0 handled by the PHP SDK:
            <a href="<?php echo $loginUrl; ?>"><img src="assets/images/fb-connect-large.png" /> </a>
          </div>
            
            <?php endif ?>
      </div>
  </div> <!-- row -->

  <!-- Example row of columns -->
  <div class="row">
      <div class="span6">
          
<!-- Necessary Scripts -->
<div id="fb-root" > </div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    

          <!-- FB Buttons and G+ -->
          
        <div id="social-buttons">
            <div class="span1">
                <div class="fb-like" data-href="http://facebook.com/pages/Stadioom/168539803210962" data-send="false" data-layout="box_count" data-width="100" data-show-faces="true"></div>
            </div>
            
            <!-- http://www.seedshock.com/fb/login -->

            <div class="span1">
                <script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
              <a href="https://twitter.com/share" class="twitter-share-button"
                 data-url="http://stadioom.com"
                 data-counturl="http://stadioom.com"
                 data-via="stadioom"
                 data-related="seedshock: Company behind Stadioom, norumoreno:Co-founder and CEO of SeedShock, justfaceit_kr: Co-founder and CTO of SeedShock"
                 data-count="vertical">Tweet</a>
            </div>
            <div class="span1">
                <g:plusone size="tall"></g:plusone>
                <!-- Place this render call where appropriate // BEST after last g plus button -->
                <script type="text/javascript">
                  (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
                </script>
            </div>
        </div>
      </div>
        <div class="span10">
          <h2>Changing paradigms...</h2>
          <p>Stadioom is the ultimate way to bring competitive sports online.</p>
          <!-- <p><a class="btn" href="#">Read more &raquo;</a></p> -->
        </div>

  </div>

</div> <!-- /container -->