
<h2>New Match</h2>

<form action="" class="form-stacked">
    <fieldset>
      <legend>Register your match
      </legend>
<!--      <div class="clearfix">
        <label for="xlInput3">X-Large input</label>
        <div class="input">
          <input class="xlarge" id="xlInput3" name="xlInput3" size="30" type="text" />
        </div>
      </div> /clearfix -->
      <div class="clearfix">
        <label for="stackedSelect">Select Sport</label>
        <div class="input">
          <select name="stackedSelect" id="stackedSelect">

            <?php foreach ($sportsList as $sport):?>
            <option value="<?php echo $sport->getId(); ?>"><?php echo $sport->getName(); ?></option>
            <?php endforeach;?>
            
          </select>
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
      
    </fieldset>
</form>
    
Team A <br />

Team B<br />

Score<br />

Start Date<br />

End Date<br />

Brand<br />

Social Media<br />
