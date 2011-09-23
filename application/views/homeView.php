
<div class="container">

  <!-- Main hero unit for a primary marketing message or call to action -->

  <div class="row">
      <div class="span8">
          Logo goes here
      </div>
      <div class="span8 showgrid">
          <div class="signup-unit">
              
            <?php
            $formAttributes = array('class' => 'form-stacked', 'id' => 'signup-form');

            echo form_open('home', $formAttributes);
            
            echo validation_errors();
            ?>
              
            <fieldset>
              <legend>Register for the closed beta!</legend>
              <div class="clearfix">
                <div class="input">
                  <input class="xlarge" id="signup-fullName" name="fullName" value="<?php echo set_value('fullName'); ?>" size="30" type="text" placeholder="Full Name"/>
                  
                </div>
              </div><!-- /clearfix -->
              <div class="clearfix">
                <div class="input">
                  <input class="xlarge" id="signup-email" name="email" value="<?php echo set_value('email'); ?>" size="30" type="text" placeholder="e-mail"/>
                  <span id="email_verify" class="verify"></span>
                </div>
              </div><!-- /clearfix -->
              <div class="clearfix">
                <div class="input">
                  <input class="xlarge" id="signup-password" name="password" size="30" type="password" placeholder="Password"/>
                </div>
              </div><!-- /clearfix -->
              <div class="clearfix">
              <button class="btn signup large rightAlign">Sign Up!</button>
              </div><!-- /clearfix -->
          </div>
      </div>
  </div> <!-- row -->

  <!-- Example row of columns -->
  <div class="row">
      <div class="span6">
          
          <!-- Necessary Scripts -->
          
          <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js"></script>
      
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#appId=194277843976863&xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



<!-- Place this render call where appropriate -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>


          <!-- FB Buttons and G+ -->
          
        <div id="social-buttons">
            <div class="span1">
                <fb:like href="http://www.stadioom.com" send="false" layout="box_count" width="20" show_faces="true"></fb:like> <!-- Place this tag where you want the +1 button to render -->
            </div>
            
            <!-- http://www.seedshock.com/fb/login -->

            <div class="span1">
                <script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
              <a href="https://twitter.com/share" class="twitter-share-button"
                 data-url="http://seedshock.com"
                 data-counturl="http://seedshock.com"
                 data-via="stadioom"
                 data-related="seedshock: Company behind Stadioom, norumoreno:Co-founder and CEO of SeedShock, justfaceit_kr: Co-founder and CTO of SeedShock"
                 data-count="vertical">Tweet</a>
            </div>
            <div class="span1">
                <g:plusone size="tall"></g:plusone>
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