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
    
    <!-- Error Messages -->
    <div id="matchSuccess" class="alert-message success" style="display: none">
        <a class="close" href="">×</a>
        <p><strong>Hooray!</strong> Your match has been registered!.</p>
    </div>
    <div id="fbShareSuccess" class="alert-message info" style="display: none">
        <a class="close" href="">×</a>
        <p><strong>Sweet!</strong> This match was shared on Facebook as well.</p>
    </div>
    <div id="fbErrorDiv" class="alert-message warning" style="display: none">
        <a class="close" href="">×</a>
        <p><strong>Oh-Oh!</strong> There was a problem sharing to Facebook!.</p>
    </div>
    <div id="sponsorErrorDiv" class="alert-message error" style="display: none">
        <a class="close" href="">×</a>
        <p><strong>Oops!</strong> You forgot to choose a sponsor!.</p>
    </div>
    <div id="teamAErrorDiv" class="alert-message error" style="display: none">
        <a class="close" href="#">×</a>
        <p><strong>Oops!</strong> You need to choose the players of team A.</p>
    </div>
    <div id="teamBErrorDiv" class="alert-message error" style="display: none">
        <a class="close" href="#">×</a>
        <p><strong>Oops!</strong> You need to choose the players of team B.</p>
    </div>
    


    <form id="newMatchForm" action="" class="form-stacked">
        <fieldset>
          <div class="clearfix">
            <label for="stackedSelect">Select Sport</label>
            <div class="input">
              <select name="sportSelect" id="sportSelect" class="large">

                <?php foreach ($sports as $sport):?>
                 <?php  $img= "/assets/images/sports/icons/icon_game_".$sport->getStringId()."_51x51.png"; ?>
                <option class="optionWithSportIcon" style="background-image:url(<?php echo $img; ?>);" value="<?php echo $sport->getId(); ?>"> <?php echo $sport->getName(); ?></option>
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
                        <input  checked="yes" type="radio" name="belongTeam" value="teamA">
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
              <div id="sponsorSelect" data-controls-modal="sponsors-modal">
                  <p class="sponsorSelecText">Select Match Sponsor</p>
              </div>
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
                <img src="/assets/images/social/facebook_32.png" /><div id="fbSwitch" class="inline"> </div>
                <img src="/assets/images/social/twitter_32.png" /><div id="twitterSwitch" class="inline"> </div>
            </div>
          <div class="actions center">
                    <input id="submitMatch" type="submit" class="btn primary large" value="Publish" />

          </div> <!-- actions -->
        </fieldset>
    </form>

    <div id="results">
    </div>

    Score<br />

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


<!--  JS window variables -->
<script type="text/javascript">
    //Domain for posts
    var baseSSLUrl = '<?php echo $this->config->item('base_ssl_url'); ?>';
    var baseUrl = '<?php echo $this->config->item('base_url'); ?>';
    
    //FBUid
    var user = {
 
        id: '<?php echo $session['user']['id']; ?>',
        name: '<?php echo $session['user']['fullName']; ?>',
        fbId: '<?php echo $session['fbUser']['id']; ?>'
    };
    
    //Friends Selector
    var teamAFBSelector, teamBFBSelector;
    
    
    //Sport and Brands
    var sportsList, selectedSponsor, selectedSportId;
    var tempSelectedSponsor; // to temporarily use in the modalbox before user press ok
    var sportBrandsJsonReq; //Container of XHR object for brands json
    
    selectedSponsor = null;
    sportBrandsJsonReq = null;
    
    var sponsors; // To use after change on dropdown select
    var sponsorPicsFolder;
    var sponsorBannersFolder;
    
    
    //Scores
    var scoreA = 0;
    var scoreB = 0;

    sponsorPicsFolder='/assets/images/sponsors/';
    sponsorBannersFolder=sponsorPicsFolder+'banners/';
    sponsorShareIconsFolder=sponsorPicsFolder+'shareicons/';
    
    selectedSportId =  <?php echo $sports[0]->getId(); ?>;

    sportsList = [];
    
    // FB and twitter switch
    var twitterShare = false;
    var FBShare = true;
    
<?php foreach ($sports as $sport):?>
    sportsList[<?php echo $sport->getId(); ?>] = [];
    sportsList[<?php echo $sport->getId(); ?>]['name'] = '<?php echo $sport->getName(); ?>';
    sportsList[<?php echo $sport->getId(); ?>]['stringId'] = '<?php echo $sport->getStringId(); ?>';
<?php endforeach;?>
</script>



<!--  modal content -->
          <div id="sponsors-modal" class="modal hide fade">
            <div class="modal-header">
              <a href="#" class="close">&times;</a>
              <h3>Match Sponsor</h3>
            </div>
            <div class="modal-body">
                <div id="sponsors-list">
                <p>Loading sponsors... </p>
                <img id="sponsorsLoader" src="/assets/images/loader2.gif"/>
                </div>
            </div>
            <div id="sponsors-modal-footer" class="modal-footer">
              
            </div>

          </div>
<!--  END OF modal content -->



<script type="text/javascript">
    //Add OK button to modal when shown and add JS function (click listener and OK click)
    $('#sponsors-modal').bind('show', function () {
        $('#sponsors-modal-footer').html('<a href="#" id="sponsorOK" class="btn primary">OK</a>');
        
          $('.sponsorItem').click(function() {
              $('.sponsorItem').removeClass('clickedSponsor');
            window.tempSelectedSponsor = $(this).data('sponsor');
            //console.log (window.tempSelectedSponsorId );
            $(this).addClass('clickedSponsor');
          });
          
          
            $('#sponsorOK').click(function() {
                if(tempSelectedSponsor!=null){
                    window.selectedSponsor = window.tempSelectedSponsor;
                    //console.log(window.selectedSponsor);
                    window.hideModal();
                }else{
                    alert('You didn\'t choose any sponsor!');
                }       

            });
        });
    
    updateMatchSponsorBanner = function(){
        imageString = window.sponsorBannersFolder+window.selectedSponsor.stringId+'_banner'+'.png';
        
        $('#sponsorSelect').html(
        '<img class="sponsorBanner" src="'+imageString+'" />'
        );
    }
        
    //Remove OK button from modal when hidden
    $('#sponsors-modal').bind('hide', function () {
                  
        if(window.selectedSponsor!=null){
            updateMatchSponsorBanner();
        }
        
        $('#sponsors-modal-footer').html('');

    });
    
    
    
    //Auxiliar function to manually hide Modal box for sponsors
    hideModal = function (){
        try{
            $('#sponsors-modal').modal('hide');
        }catch(err){
            console.log('Modal Box already closed');
        }
    }
  
    $('#fbSwitch').iphoneSwitch("on", 
     function() {
       //alert('FBOn');
       FBShare=true;
      },
      function() {
       //alert('FBOff');
       FBShare=false;
      },
      {
        switch_height: 27,
		switch_width: 94
      });
      
      
     $('#twitterSwitch').iphoneSwitch("off", 
     function() {
       alert('TwitterOn');
       twitterShare=true;
      },
      function() {
       alert('Twitterffn');
       twitterShare=false;
      },
      {
        switch_height: 27,
		switch_width: 94
      });
</script>


<script type="text/javascript">
    
    var appId = <?php echo $this->config->item('fbAppId'); ?>;
</script>


<script src="/assets/js/createMatch.js"></script>
<script src="/assets/js/createMatchFBFriendSelector.js"></script>



