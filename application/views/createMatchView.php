
<h2>New Match</h2>

Total points: {$totalPoints} 
<br />

Select sport

<form action="" class="form-stacked">
    <fieldset>
      <legend>Example form legend</legend>
      <div class="clearfix">
        <label for="xlInput3">X-Large input</label>
        <div class="input">
          <input class="xlarge" id="xlInput3" name="xlInput3" size="30" type="text" />
        </div>
      </div><!-- /clearfix -->
      <div class="clearfix">
        <label for="stackedSelect">Select</label>
        <div class="input">
          <select name="stackedSelect" id="stackedSelect">
            <?php foreach ($sportsList as $sport):?>

            <option><?php echo $sport['name'];?></option>
            <input type="hidden" name="id" value="<?php echo $sport['id'];?>" />
            
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
                <input type="checkbox" name="optionsShare" value="fb" />
                <span>Facebook<img src="/assets/images/facebook-icon-small.jpg" /></span>
              </label>
            </li>
            <li>
              <label>
                <input type="checkbox" name="optionsShare" value="twitter" />
                <span>Twitter<img src="/assets/images/twitter-icon-small.png" /></span>
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
