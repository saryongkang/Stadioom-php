<div id="middleContent"class="span8">
    
    
    <?php foreach ($matches as $match):?>
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
    <?php endforeach;?>
    
</div>
<div class="span4">
    <h2>Teams Info </h2>
    <div id="teamADesc" class="teamDesc">
        <div class="teamName">Team A</div>
        <div id="teamAPlayersList" class="playersList">
            No players Selected
        </div>
    </div>
    <div id="teamBDesc" class="teamDesc">
        <div class="teamName">Team B</div>
        <div id="teamBPlayersList" class="playersList">
            No players Selected
        </div>
    </div>
</div>