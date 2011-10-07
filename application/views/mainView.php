<div class="span8">
    <h2><?php echo $userdata['fullName'] ?>'s Sport Card!</h2>

    Total points: {$totalPoints}
    <br />


    <div id='last-match' class='mini-section'>
      <div id='last-match-header' class='minisection-header'>
        <div id='last-match-header-text' class="minisection-header-text">
           Last match:
        </div>
        <div class="minisection-view-all">
          <a href="#">View all matches</a>
        </div>
      </div>

      <div class='match-banner'>
        <div class='match-banner-title'>
        {$matchSport} Match, sponsored by {$matchSponsor} 
        </div>
        <div class='match-banner-body'>
          <div class='match-team-left' id='match-player1'>
            <img class='match-player-img' id='match-player1-img' src="assets/images/default_user_100x100.png" width="50" height="50" />
            <div class="match-player-text">
            {$player1Name} <br />
            {$player1Score}
            </div>
          </div><!-- End match-teamleft -->
          <div class='match-vs'>
          VS
          </div>

          <div class='match-team-right' id='match-player2'>
            <div class="match-player-text">
            {$player2Name} <br />
            {$player2Score}
            </div>
            <img class='match-player-img' id='match-player2-img' src="assets/images/default_user_100x100.png" width="50" height="50" />
          </div><!-- End match-player2 -->

        </div> <!-- End match-banner-body -->
      </div> <!-- End match-banner -->

    </div>  <!-- End last-match -->

</div>
<div class="span4">
  Ads
</div>
