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
          <select name="stackedSelect" id="stackedSelect" class="large">

            <?php foreach ($sportsList as $sport):?>
            <option value="<?php echo $sport->getId(); ?>"><?php echo $sport->getName(); ?></option>
            <?php endforeach;?>
            
          </select>
        </div>
      </div><!-- /clearfix -->
      
      <div class="clearfix">
      <label id="optionsRadio">List of options</label>
          <div class="input">
            <ul class="inputs-list">
                <li>
                  <label>
                    <input type="radio" checked="" name="belongTeam" value="teamA">
                    <span>Team A</span>
                  </label>
                </li>
                <li>
                  <label>
                    <input type="radio" checked="" name="belongTeam" value="teamB">
                    <span>Team B</span>
                  </label>
                </li>
            </ul>
          </div>
      </div><!-- /clearfix -->
     
      <div class="clearfix">
       <label for="teamsPlayers">Who's playing?</label>
       <div class="input-append">
        <button id="playersA" class="btn success large" >Team A</button><label class="add-on active xlarge"><input type="radio" name="" id="" value="" checked="checked"></label>
       </div>
       VS
        <button id="playersB" class="btn danger large" >Team B</button>
      </div><!-- /clearfix -->
      
      <div class="clearfix">
        <label>Date range</label>
        <div class="input">
          <div class="inline-inputs">
            <input class="small" type="text" value="May 1, 2011" />
            <input class="mini" type="text" value="12:00am" />
            to
            <input class="small" type="text" value="May 8, 2011" />
            <input class="mini" type="text" value="11:59pm" />
            <span class="help-inline">All times are shown as Pacific Standard Time (GMT -08:00).</span>
          </div>
        </div>
      </div><!-- /clearfix -->
      
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
      </div><!-- /clearfix -->
      
      <div class="actions">
        <input type="submit" class="btn primary center" value="Save changes">
      </div> <!-- actions -->
    </fieldset>
</form>

<div id="results">
</div>


Score<br />

Brand<br />

Social Media<br />

<script src="/assets/js/createMatch.js"></script>