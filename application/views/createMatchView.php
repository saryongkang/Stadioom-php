<div id="middleContent"class="span8">

    <div id="fb-root"> &nbsp; </div>


    <!-- Necessary for the friends selector -->
    <!-- Markup for These Days Friend Selector -->
            <div id="TDFriendSelector">
                <div class="TDFriendSelector_dialog">
                    <a href="#" id="TDFriendSelector_buttonClose">x</a>
                    <div class="TDFriendSelector_form">
                        <div class="TDFriendSelector_header">
                            <p>Select your friends</p>
                        </div>
                        <div class="TDFriendSelector_content">
                            <p>Then you can invite them to join you in the app.</p>
                            <div class="TDFriendSelector_searchContainer TDFriendSelector_clearfix">
                                <div class="TDFriendSelector_selectedCountContainer"><span class="TDFriendSelector_selectedCount">0</span> / <span class="TDFriendSelector_selectedCountMax">0</span> friends selected</div>
                                <input type="text" placeholder="Search friends" id="TDFriendSelector_searchField" />
                            </div>
                            <div class="TDFriendSelector_friendsContainer"></div>
                        </div>
                        <div class="TDFriendSelector_footer TDFriendSelector_clearfix">
                            <a href="#" id="TDFriendSelector_pagePrev" class="TDFriendSelector_disabled">Previous</a>
                            <a href="#" id="TDFriendSelector_pageNext">Next</a>
                            <div class="TDFriendSelector_pageNumberContainer">
                                Page <span id="TDFriendSelector_pageNumber">1</span> / <span id="TDFriendSelector_pageNumberTotal">1</span>
                            </div>
                            <a href="#" id="TDFriendSelector_buttonOK">OK</a>
                        </div>
                    </div>
                </div>
            </div>
    <!-- END of FriendsSelector DIVS -->




    <h2>New Match</h2>

    <form action="" class="form-stacked">
        <fieldset>
    <!--      <legend>Register your match
          </legend>-->
    <!--      <div class="clearfix">
            <label for="xlInput3">X-Large input</label>
            <div class="input">
              <input class="xlarge" id="xlInput3" name="xlInput3" size="30" type="text" />
            </div>
          </div> /clearfix -->
          <div class="clearfix">
            <label for="stackedSelect">Select Sport</label>
            <div class="input">
              <select name="sportSelect" id="sportSelect" class="large">

                <?php foreach ($sportsList as $sport):?>
                <option value="<?php echo $sport->getId(); ?>"><?php echo $sport->getName(); ?></option>
                <?php endforeach;?>

              </select>
            </div>
          </div><!-- /clearfix -->

          <div class="clearfix">
          <label id="optionsRadio">On which team are you playing? </label>
              <div class="input">
                <ul class="inputs-list">
                    <li>
                      <label>
                        <input type="radio" name="belongTeam" value="teamA">
                        <span>Team A</span>
                      </label>
                    </li>
                    <li>

                      <label>
                        <input type="radio" name="belongTeam" value="teamB">
                        <span>Team B</span>
                      </label>
                    </li>
                    <li>
                      <label>
                        <input type="radio" name="belongTeam" value="none">
                        <span>None</span>
                      </label>
                    </li>
                </ul>
              </div>
          </div><!-- /clearfix -->

          <div class="clearfix">
           <label for="teamsPlayers">Who's playing?</label>
            <button id="playersA" class="btn success large" >Team A</button>
           VS
            <button id="playersB" class="btn danger large" >Team B</button>
          </div><!-- /clearfix -->

          <div class="clearfix">
            <label>Date</label>
            <div class="input">
              <div class="inline-inputs">
                <input class="small" type="text" value="May 1, 2011" />
                <input class="mini" type="text" value="2:00pm" />
                <span class="help-inline">Time is Pacific Standard Time (GMT -08:00).</span>
              </div>
            </div>
          </div>
          
          <div class="clearfix">
              <label> Sponsor </label>
              <a href="#">
              <div class="sponsorSelect" data-controls-modal="sponsors-modal">
                  <p class="sponsorSelecText">Select Match Sponsor</p>
              </div>
              </a>
          </div>
          <!-- /clearfix -->
    <!--      
          <div class="clearfix">
            <label id="optionsCheckboxes">Share in...</label>
            <div class="input">
              <ul class="inputs-list">
                <li>
                  <label>
                    <input type="checkbox" name="optionsShare" value="fb" checked/>
                    <span><img src="/assets/images/social/facebook_16.png" />Facebook</span>
                  </label>
                </li>
                <li>
                  <label>
                    <input type="checkbox" name="optionsShare" value="twitter" checked/>
                    <span><img src="/assets/images/social/twitter_16.png" />Twitter</span>
                  </label>
                </li>
              </ul>
            </div>
          </div> /clearfix -->
            <div class="clearfix">
                <img src="/assets/images/social/facebook_32.png" /><div id="fbSwitch" class="inline"> </div><div id="ajaxd" class="inline"> </div>
                <img src="/assets/images/social/twitter_32.png" /><div id="twitterSwitch" class="inline"> </div><div id="ajaxd2" class="inline"> </div>
            </div>
          <div class="actions center">
                    <input id="sendMatch" type="submit" class="btn primary large" value="Publish" />

          </div> <!-- actions -->
        </fieldset>
    </form>

    <div id="results">
    </div>


    Score<br />

    Brand<br />

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


<!--  modal content -->
          <div id="sponsors-modal" class="modal hide fade">
            <div class="modal-header">
              <a href="#" class="close">&times;</a>
              <h3>Match Sponsor</h3>
            </div>
            <div class="modal-body">
                <div id="#sponsors-list">
                <p>For all sponsors...</p>
                </div>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn primary">OK</a>
              <a href="#" class="btn secondary">Cancel</a>
            </div>
          </div>

<script type="text/javascript">
    var sportsList;
<?php foreach ($sportsList as $sport):?>
    sportsList[<?php echo $sport->getId(); ?>]['name'] = <?php echo $sport->getName(); ?>
    sportsList[<?php echo $sport->getId(); ?>]['stringId'] = <?php echo $sport->getStringId(); ?>
<?php endforeach;?>
</script>

<script type="text/javascript">
  
    $('#fbSwitch').iphoneSwitch("on", 
     function() {
       $('#ajaxd').text('');
      },
      function() {
       $('#ajaxd').text('');
      },
      {
        switch_height: 27,
		switch_width: 94
      });
      
      
     $('#twitterSwitch').iphoneSwitch("off", 
     function() {
       $('#ajaxd2').text('');
      },
      function() {
       $('#ajaxd2').text('');
      },
      {
        switch_height: 27,
		switch_width: 94
      });
</script>


<script type="text/javascript">
    var appId='205626906162291';
</script>

<script src="/assets/js/createMatch.js"></script>



