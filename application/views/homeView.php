
<div class="container">

  <div class="row">
      <div id="emptySpace" class="span16">
          &nbsp;
      </div>
  </div>
  
  <div class="row">
      <div class="span9">
          &nbsp;
      </div>
      <div class="span7 showgrid">
            <a href="<?php echo $loginUrl; ?>"><img src="/assets/images/fb-connect-large.png" /> </a>
      </div>
  </div> <!-- row -->

  <!-- Example row of columns -->
  <div class="row">
      <div class="span9">
          
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
        <div id="social-buttons" class="row">
            <div class="span3"> &nbsp;</div>
            <div class="span1">
                <div class="fb-like" data-href="http://facebook.com/pages/Stadioom/168539803210962" data-send="false" data-layout="box_count" data-width="50" data-show-faces="true"></div>
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
            <div class="span3"> &nbsp;</div>
        </div>
      </div>
        <div class="span7">
          <h2>Changing paradigms...</h2>
          <p>Stadioom is the ultimate way to bring competitive sports online.</p>
          <!-- <p><a class="btn" href="#">Read more &raquo;</a></p> -->
        </div>

  </div>

</div> <!-- /container -->