<div id="middleContent"class="span8">
    
    
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