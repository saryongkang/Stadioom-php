<?php

header('Content-Type: text/html; charset=utf8');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>
  <head>
    <!-- or -->
    <meta http-equiv="content-type" content="text/html; charset=utf8" />
  </head>
  <body>
      <?php echo 'Normal> Norú' ?>
      <?php echo '<br />' ?>
      <?php echo 'utf8_encode> '.utf8_encode('Norú') ?>
      <?php echo '<br />' ?>
      <?php echo 'utf8_encode utf8_decode> '.utf8_decode(utf8_encode('Norú')) ?>
      <?php echo '<br />' ?>
      <?php
      $email1= 'wegra91@seedshock.com';
      $email2= 'wegra92@seedshock.com';
      
       $con = mysql_connect('107.20.213.215', 'matchadmin', 'seedshock~!@') or die('Could not connect to the server!');
       mysql_select_db('Stadioom') or die('Could not select a database.');
       $result = mysql_query("INSERT INTO `Stadioom`.`User`
                    (
                    `fbId`,
                    `fbLinked`,
                    `fbAuthorized`,
                    `password`,
                    `name`,
                    `email`,
                    `gender`,
                    `dob`,
                    `verified`,
                    `created`)
                    VALUES
                    (
                    '123', 0, 0, 'utf8encode', '" . utf8_encode('Norú') . "', '".$email1."', 0, '2000-01-01', 0, '2011-01-01'
                    )") or die('A error occured: ' . mysql_error());
      
       $result = mysql_query("INSERT INTO `Stadioom`.`User`
                    (
                    `fbId`,
                    `fbLinked`,
                    `fbAuthorized`,
                    `password`,
                    `name`,
                    `email`,
                    `gender`,
                    `dob`,
                    `verified`,
                    `created`)
                    VALUES
                    (
                    '123', 0, 0, 'nothing', '" . 'Norú' . "', '".$email2."', 0, '2000-01-01', 0, '2011-01-01'
                    )") or die('A error occured: ' . mysql_error());
       
       $result = mysql_query('SELECT * FROM `Stadioom`.`User` WHERE `email` = "'.$email1.'"') or die('A error occured: ' . mysql_error());
       $row = mysql_fetch_array($result);
       
       echo 'DB Select Normal> '. $row['name'];
       echo '<br />';
       echo 'DB Select Normal + utf8_decode> '. utf8_decode($row['name']);
        echo '<br />';
        
        $result = mysql_query('SELECT * FROM `Stadioom`.`User` WHERE `email` = "'.$email2.'"') or die('A error occured: ' . mysql_error());
        
       $row = mysql_fetch_array($result);
       
       echo 'DB Select utf8_encode> '. $row['name'];
        echo '<br />';
        echo 'DB Select utf8_encode utf8_decode> '. utf8_decode($row['name']);
        echo '<br />';
        
       mysql_close($con);
       
      ?>
      <?php echo '<br />' ?>
      <?php echo 'SQL Queries performed' ?>
     
  </body>
</html>