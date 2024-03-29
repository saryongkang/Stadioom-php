<div class="span8">
    <h2><?php echo $session['user']['fullName'] ?>'s Sport Card!</h2>

    Total points: {$totalPoints}
    <br />

    
    
    <div id='last-match' class='mini-section'>
        <?php if (sizeof($matches)>0): ?>
        
      <div id='last-match-header' class='minisection-header'>
        <div id='last-match-header-text' class="minisection-header-text">
            Last match
        </div>
        <div class="minisection-view-all">
          <a href="#">View all matches</a>
        </div>
      </div>
      <?php foreach ($matches as $match):?>
      <div class='match-banner'>
        <div class='match-banner-title'>
        <?php echo $match['title'];?>
        </div>
        <div class='match-banner-body'>
          <div class='match-team-left' id='match-player1'>

            <img class='match-player-img' id='match-player1-img' src="<?php echo $match['summaryPlayersAPic'] ?>" width="50" height="50" />
            <div class="match-player-text">
               
            <?php echo $match['summaryPlayersAText'] ?> <br />
            <?php echo $match['scoreA'] ?>
            </div>
          </div><!-- End match-teamleft -->
          <div class='match-vs'>
          VS
          </div>

          <div class='match-team-right' id='match-player2'>
            <div class="match-player-text">
            <?php echo $match['summaryPlayersBText'] ?> <br />
            <?php echo $match['scoreB'] ?>
            </div>
            <img class='match-player-img' id='match-player2-img' src="<?php echo $match['summaryPlayersBPic'] ?>" width="50" height="50" />
          </div><!-- End match-player2 -->

        </div> <!-- End match-banner-body -->
      </div> <!-- End match-banner -->
      
      <?php echo $match['playersADetailDiv'] ?>
      <?php echo $match['playersBDetailDiv'] ?>
    <?php endforeach;?>
      <?php else: ?>
        You didn't play any games yet.
    <?php endif; ?>

    </div>  <!-- End last-match -->
    
</div>
<div class="span4">
  Ads
</div>
