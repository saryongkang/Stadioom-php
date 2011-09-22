 <html xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
      <title>Stadioom - Powering up sports</title>
      
    </head>
    <body>
<div id="home-header">
    <div id="stadioom-title"><h1>Stadioom</h1> </div>
    
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
