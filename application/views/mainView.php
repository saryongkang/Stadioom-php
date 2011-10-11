<div class="span8">
    <h2><?php echo $session['user']['fullName'] ?>'s Sport Card!</h2>

    Total points: {$totalPoints}
    <br />

    
    
    <div id='last-match' class='mini-section'>
        <?php if (sizeof($lastMatch)>0): ?>
        <?php $match=$lastMatch[0]; ?>
        
      <div id='last-match-header' class='minisection-header'>
        <div id='last-match-header-text' class="minisection-header-text">
           Last match:
        </div>
        <div class="minisection-view-all">
          <a href="#">View all matches</a>
        </div>
      </div>
=
      <div class='match-banner'>
        <div class='match-banner-title'>
        <?php echo $sports[$match->getSportId()-1]->getName(); ?> Match, sponsored by <?php echo $brands[$match->getBrandId()-1]->getName();?>
        </div>
        <div class='match-banner-body'>
          <div class='match-team-left' id='match-player1'>
            <img class='match-player-img' id='match-player1-img' src="/assets/images/default_user_100x100.png" width="50" height="50" />
            <div class="match-player-text">
            {$player1Name} <br />
            <?php echo $match->getScoreA() ?>
            </div>
          </div><!-- End match-teamleft -->
          <div class='match-vs'>
          VS
          </div>

          <div class='match-team-right' id='match-player2'>
            <div class="match-player-text">
            {$player2Name} <br />
            <?php echo $match->getScoreB() ?>
            </div>
            <img class='match-player-img' id='match-player2-img' src="/assets/images/default_user_100x100.png" width="50" height="50" />
          </div><!-- End match-player2 -->

        </div> <!-- End match-banner-body -->
      </div> <!-- End match-banner -->
      <?php else: ?>
        You didn't play any games yet.
    <?php endif; ?>

    </div>  <!-- End last-match -->
    
</div>
<div class="span4">
  Ads
</div>
