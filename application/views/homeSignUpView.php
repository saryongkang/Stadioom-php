 <html xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
      <title>Stadioom - Powering up sports</title>
      
      <!-- Google Analytics -->
      <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25850852-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    </head>
    <body>
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

<div id="home-header">
    <div id="stadioom-title"><h1>Stadioom</h1> </div>
    <div id="social-buttons">
        <fb:like href="http://stadioom.com" send="false" layout="button_count" width="30" show_faces="true" font="lucida grande"></fb:like> <!-- Place this tag where you want the +1 button to render -->
        <g:plusone size="medium"></g:plusone>
    </div>
</div>
      <h2>Powering up sports</h2>
      <div id='homeSignup-text'> Bla bla bla bla bla </div>
      <script>
         FB.init({ 
            appId:'<?php echo $fbAppId ?>', cookie:true, 
            status:true, xfbml:true 
         });
      </script>
      <br />
      <div id="signup-area">
          Create account
          <fb:login-button perms="email,user_checkins,user_likes,user_interests,user_hometown,user_location,user_education_history,user_birthday,user_activities,offline_access,publish_stream">Login with Facebook</fb:login-button>
      </div>
   </body>
 </html>
