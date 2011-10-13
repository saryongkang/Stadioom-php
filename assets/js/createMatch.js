/*jslint devel: true, bitwise: false, undef: false, browser: true, continue: false, debug: false, eqeq: false, es5: false, type: false, evil: false, vars: false, forin: false, white: true, newcap: false, nomen: true, plusplus: false, regexp: true, sloppy: true */
/*globals $, jQuery, FB, TDFriendSelector */
(function () {
	var e = document.createElement('script');
	e.async = true;
	e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);
}());

//Errors
$(".close").click(function (event) {
    event.preventDefault();
  $(this).parent().fadeOut();
});



//Sponsors
//
//Modal Box
$('#sponsors-modal').modal({
  keyboard: true,
  backdrop: true
});

// Get sponsors per sport
updateSponsorsDiv = function (){
     window.sportBrandsJsonReq = jQuery.getJSON(window.baseSSLUrl+'api/sport/brands?id='+window.selectedSportId, function(sponsors) {
      window.sponsors = sponsors.data;
      sponsors=sponsors.data;
      //console.log(sponsors.length);
      $('#sponsors-list').html('');

     for (i = 0, len = sponsors.length; i < len; i += 1) {
         imageString = window.sponsorBannersFolder+sponsors[i].stringId+'_banner'+'.png';
           $("#sponsors-list").append('<div id="'+sponsors[i].stringId +'-banner" class="sponsorItem"><img class="sponsorBanner" src="'+imageString+'" /> ' + '</div>');
           $('#'+sponsors[i].stringId +'-banner').data('sponsor', sponsors[i]);
     }

    });
    return window.sportBrandsJsonReq;
};


reEnableSponsorSelect= function(){
//    data-controls-modal="sponsors-modal"
    $('#sponsorSelect').attr('data-controls-modal', 'sponsors-modal');  
    $('#sponsorSelect').html('<p class="sponsorSelecText">Select Match Sponsor</p>');
}

disableSponsorSelect= function(){
    $('#sponsorSelect').html('<p class="sponsorSelecText">Loading sponsors... </p><img id="sponsorsLoader" src="/assets/images/loader2.gif"/>');
    $('#sponsorSelect').removeAttr('data-controls-modal');
}


sportsChanged = function() {
    disableSponsorSelect();
    
    window.selectedSportId = $("#sportSelect").val();
    
    sportBrandsJson = window.updateSponsorsDiv();
    
    sportBrandsJson.complete(function() {
        window.reEnableSponsorSelect();
        window.sportBrandsJsonReq = null;
    });
}

// to check on page load
sportsChanged();

//Listener for change in sport select

$("#sportSelect").change( function(){
    if(window.sportBrandsJsonReq!=null){
        window.sportBrandsJsonReq.abort();
    }
    
    sportsChanged();
    window.selectedSponsor=null;
});


$('#submitMatch').click(function(event) {
    event.preventDefault();
    var errors = [];
    $("#sponsorErrorDiv").hide();
    $("#teamAErrorDiv").hide();
    $("#teamBErrorDiv").hide();
    $("#fbErrorDiv").hide();
    $("#matchSuccess").hide();
    $("#fbShareSuccess").hide();
    $("#scoreAErrorDiv").hide();
    $("#scoreBErrorDiv").hide();
    $("#dateTimeErrorDiv").hide();
    
    window.scoreA = parseInt($('#scoreA').val());
    window.scoreB = parseInt($('#scoreB').val());
    window.matchDateTime = $('#matchDateTime').datetimepicker('getDate');
    //console.log(window.isDateTimeSelected);
//    console.log(scoreA);
//    console.log(scoreB);
//    
    var belongTeam = window.belongTeam;
    
//    console.log('belongTeam '+belongTeam);
//    console.log('LengthA '+window.teamAFBSelector.getselectedFriendIds().length);
//    console.log('LengthB '+window.teamBFBSelector.getselectedFriendIds().length);
//    
    //Validation
    if(!window.scoreA === undefined || isNaN(window.scoreA)){
        errors.push( {type: 'scoreA', value: true});
        $("#scoreAErrorDiv").fadeIn();
    }else{
        //TODO: code for validation
    }
    
    if(window.scoreB === undefined || isNaN(window.scoreB) ){
        errors.push( {type: 'scoreB', value: true});
        $("#scoreBErrorDiv").fadeIn();
    }else{
        //TODO: code for validation
    }
    
    if(window.selectedSponsor==null){
        errors.push( {type: 'sponsor', value: true});
        $("#sponsorErrorDiv").fadeIn();
    }
    
    if(window.teamAFBSelector.getselectedFriendIds().length <1){
        if(belongTeam != 1){
             errors.push( {type: 'teamA', value: true});
             $("#teamAErrorDiv").fadeIn();
        }
    }
    
    if(window.teamBFBSelector.getselectedFriendIds().length <1){
        if(belongTeam != 2){
             errors.push( {type: 'teamB', value: true});
             $("#teamBErrorDiv").fadeIn();
        }
    }
    
    if(window.isDateTimeSelected === false){
         errors.push( {type: 'dateTime', value: true});
         $("#dateTimeErrorDiv").fadeIn();
    }
    
    //console.log(errors);
    //console.log("Errors: "+ errors.length);

    if(errors.length<1){
        
        var teamAFBPlayers = window.teamAFBSelector.getselectedFriendIds().slice(0);
        var teamBFBPlayers = window.teamBFBSelector.getselectedFriendIds().slice(0);
                
        var currentDate = new Date();
        var title = window.selectedSponsor.id +" " + window.selectedSportId + " match: " +window.scoreA +" - "+window.scoreB;
        
        var cct = $.cookie('safesdskstad');
        
        
        
        
        //console.log(belongTeam);
        
        if(belongTeam == 1){
            teamAFBPlayers.push(window.user['fbId']);
        }else if(belongTeam == 2){
            teamBFBPlayers.push(window.user['fbId']);
        }
//        console.log(window.user['fbId']);
//        console.log('ScoreA ='+window.scoreA + " " + 'ScoreB ='+window.scoreB);
//        console.log('Belongteam ='+belongTeam);

        var startDateTimeUTC = window.matchDateTime.format("yyyy-mm-dd hh:mm:ss", true);
//        console.log ("UTC: "+startDateTimeUTC);
//        console.log ("Local: "+window.matchDateTime.format("yy-mm-dd hh:mm:ss"));
        params = {
            "sportId" : window.selectedSportId,
            "brandId": window.selectedSponsor.id ,
//            "title" : null,
            //@"1", @"leagueType",
            "userId" : window.user['id'],
            "scoreA" : window.scoreA,
            "scoreB" : window.scoreB,
            //@"", @"memberIdsA",
            "memberFbIdsA[]" : teamAFBPlayers,
            //@"", @"memberIdsB",
            "memberFbIdsB[]" : teamBFBPlayers,
            "started" : startDateTimeUTC,
            "sdsk_stad_tok": cct
//            "ended" : startDateTimeUTC
        };

        //Make AJAX POST
        submitMatch = $.post(baseSSLUrl+'api/match', params);
        
        //Show success message and post to FB
        submitMatch.success( function(){
            $("#matchSuccess").fadeIn();
            
            if(FBShare==true){
                try{

                    postToWallUsingFBApi();
                }catch(error){
                    $("#fbErrorDiv").fadeIn();
                    console.log(error);
                }
            }
            
            resetForm();

        });
        
    var resetForm = function(){
        $('#scoreA').val('');
        $('#scoreB').val('');
        window.isDateTimeSelected = false;
        $('#matchDateTime').datetimepicker('setDate', (new Date()) );
        $('#matchDateTime').val('');
    }

//   NSString *caption = [NSString stringWithFormat:@"%@ just defeated %@ in a fierce %@ match.", namesInTeamA, namesInTeamB, self.game.name];
//    NSString *message = [NSString stringWithFormat:@"%@ won!", ([self.teamMembers count] > 0)? @"We" : @"I"];
//    NSString *picture = [NSString stringWithFormat:@"http://stadioom.com/assets/images/sponsors/shareicons/%@_%@_shareicon.gif", self.sponsor.name, self.game.name];
//    NSString *name = [NSString stringWithFormat:@"%@ %@ Match", self.sponsor.name, self.game.name];
//    NSString *link = [NSString stringWithFormat:@"http://stadioom.com/match/view/%@", self.matchId];
//    NSString *description = [NSString stringWithFormat:@"Final score: %@ %@ - %@ %@", namesInTeamA, self.myScore, namesInTeamB, self.opponentScore];
//        
    }
    
    var onPostToWallCompleted = function(){
        $("#fbShareSuccess").fadeIn();
    }
    var winnerTeam;
    
    var checkWinner= function(){
        //Check winner
        var belongTeam = window.belongTeam;
        var scoreA = window.scoreA;
        var scoreB = window.scoreB;
//        console.log ('scoreA '+scoreA);
//        console.log ('scoreB '+scoreB);
        if (scoreA>scoreB){
            winnerTeam = 1; //A is winner
        }else if (scoreA<scoreB){
            winnerTeam = 2; //B is winner
        }else if (scoreA==scoreB){
            winnerTeam = 3; //none
        }
        //console.log ('ScoreA: ' + scoreA + 'ScoreB: ' + scoreB);
        //console.log ('WinnerTeam: ' + winnerTeam);
        var userWinningStatus = 0; //0 didn't play //1 won //2 lost //3 tie
        //console.log ('Belongteam: ' + belongTeam);
        if (belongTeam != 0){
            if (belongTeam == winnerTeam){
                userWinningStatus = 1;
            }else if(winnerTeam==3){
                userWinningStatus = 3;
            }else{
                userWinningStatus = 2;
            }
        }else{
            userWinningStatus = 0;
        }
        
        return userWinningStatus;
    }
    
    var postToWallUsingFBApi = function(){
        
        var fbMessages = window.matchFbMessages;
        
        var sponsorShareIcon = window.baseUrl+window.sponsorShareIconsFolder+window.selectedSponsor.stringId +'_'+sportsList[selectedSportId].stringId+'_shareicon'+'.gif';
        
        //check the winning status of the player in the match
        var userWinningStatus = checkWinner();

        // User's team (to check if he played alone or with a team) singular or plural
        var numeral = "singular";
        //console.log('winningStatus: '+userWinningStatus);
        if(userWinningStatus!=0){
            if (window.userTeam.length>0) numeral="plural";
        }
        //console.log('userTeam Size '+ window.userTeam.length);

        var message; //Depending on the winning status
        var stringWinningStatus = '';
        //0 didn't play //1 won //2 lost //3 tie
        if(userWinningStatus!=0){
            switch(userWinningStatus){
            case 1:
              stringWinningStatus= 'won_'+numeral;
              break;
            case 2:
              stringWinningStatus = 'lost_'+numeral;
              break;
            case 3:
              stringWinningStatus = 'tied_'+numeral;
              //message = (singular===true) ? fbMessages['tied_singular'] : fbMessages['tied_plural'];
              break;
            }
            message = fbMessages[stringWinningStatus];
            //console.log(stringWinningStatus);
        }else{
            message = fbMessages['didntplay'];
        }
        
        
        
        var properties = {};
        var teamSummaryPlayers={};
        
        for(var i=1; i<=2; i++){
            var score;
            var teamId = '';
            var teamPlayers= null;
            if(i==1){
                teamId = 'A';
                score = window.scoreA;
                teamPlayers = window.teamAFBSelector.getselectedFriendIds();
            }else{
                teamId = 'B';
                score = window.scoreB;
                teamPlayers = window.teamBFBSelector.getselectedFriendIds();
            }
            properties['Team '+teamId] = { "text": "Score: "+score, "href": window.baseUrl};
            
            
            teamSummaryPlayers[teamId]={};
            teamSummaryPlayers[teamId]['firstPlayerName'] = '';
            
            var counter=0;
            if(window.belongTeam == i){
                properties['- '+teamId+'.'+(1)]= { "text": window.user['name'], "href": window.baseUrl+'users/'+window.user['id']};
                teamSummaryPlayers[teamId]['firstPlayerName'] = getFirstName(window.user['name']);
                counter=1;
            }
            
            var numPlayers=0;
            $('#teamMates'+teamId+' .playerInTeamList').each(function(index) {
                //alert(index + ': ' + $(this).text());
                var name = $(this).text();
                if(index===0 && (window.belongTeam != i)){
                    teamSummaryPlayers[teamId]['firstPlayerName'] = getFirstName(name);
                }
                numPlayers++;
                
                properties['- '+teamId+'.'+(index+1+counter)]= { "text": name, "href": window.baseUrl+'users/fbId/'+teamPlayers[index]};
              });
            teamSummaryPlayers[teamId]['othersCount']=((numPlayers+counter)-1);
            
            if(teamSummaryPlayers[teamId]['othersCount'] >0){
                teamSummaryPlayers[teamId]['postText'] = sprintf(fbMessages['andOthers'],teamSummaryPlayers[teamId]['firstPlayerName'],teamSummaryPlayers[teamId]['othersCount']);
            }else{
                teamSummaryPlayers[teamId]['postText'] = teamSummaryPlayers[teamId]['firstPlayerName'];
            }
            //console.log (teamSummaryPlayers[teamId]);
        }
        
        var sportName = sportsList[selectedSportId].name;
        
        firstTeamAndOthers = teamSummaryPlayers['A']['postText'];
        secondTeamAndOthers = teamSummaryPlayers['B']['postText'];
        
        if(stringWinningStatus==''){
            numeral="singular";
            if(winnerTeam==1){
                if (teamSummaryPlayers['A']['othersCount']>0) numeral="plural";
                stringWinningStatus = 'won_'+numeral;
            }else if(winnerTeam==2){
                firstTeamAndOthers = teamSummaryPlayers['B']['postText'];
                secondTeamAndOthers = teamSummaryPlayers['A']['postText'];
                if (teamSummaryPlayers['B']['othersCount']>0) numeral="plural";
                stringWinningStatus = 'won_'+numeral;
            }else{
                if (teamSummaryPlayers['A']['othersCount']>0) numeral="plural";
                stringWinningStatus = 'tied_'+numeral;
            }
        }else{
            if(belongTeam==2){
                firstTeamAndOthers = teamSummaryPlayers['B']['postText'];
                secondTeamAndOthers = teamSummaryPlayers['A']['postText'];
            }
        }
        
        //console.log(stringWinningStatus);
        
        var data=
        {
            message: message,
            //display: 'iframe',
            caption: sprintf(fbMessages['caption_'+stringWinningStatus],firstTeamAndOthers,secondTeamAndOthers,sportName),
            name: sprintf(fbMessages['title'], window.selectedSponsor.name, sportName),  
            picture: sponsorShareIcon,    
            link: window.baseUrl,  // Go here if user click the picture
            //description: "Description field",
            actions: [{ name: 'Cheer', link: window.baseUrl+'/match/id/cheer/teamA' }],
            properties: properties
        }
        //console.log(data);    
        FB.api('/me/feed', 'post', data, onPostToWallCompleted);
        $("#fbShareSuccess").fadeIn();
        
        //Send Requests
        teamA = window.teamAFBSelector.getselectedFriendIds();
        teamB = window.teamAFBSelector.getselectedFriendIds();
        if(teamA.length>0){
            FB.ui({method: 'apprequests',
            message: "We had a great match, let's follow up on Stadioom",
            to: window.teamAFBSelector.getselectedFriendIds()}, requestCallback);
        }
        if(teamB.length>0){
            FB.ui({method: 'apprequests',
            message: "We had a great match, let's follow up on Stadioom",
            to: window.teamBFBSelector.getselectedFriendIds()}, requestCallback);
        }
          
        
      
      
      
      var requestCallback = function(){
          console.log('Requests sent');
      }
    }


}); //End of Submit Match Click Event

//Change button names wen selecting belonging team

var belongTeamChanged = function(){
    var belongTeam = $('input[name=belongTeam]:checked', '#newMatchForm').val();
    var myTeamTxt = window.myTeamTxt;
    var oppTeamTxt = window.oppTeamTxt;
    var teamATxt = window.teamATxt;
    var teamBTxt = window.teamBTxt;
    var noPlayersTxt = window.noPlayersTxt;
     
    if(belongTeam == 1){
        $('.teamANameSpan').text(myTeamTxt);
        $('.teamBNameSpan').text(oppTeamTxt);
        $("#userPlayerInA").html('<img src="https://graph.facebook.com/'+window.user['fbId']+'/picture" /> ' +window.user['name']);
        if(window.teamBFBSelector.getselectedFriendIds().length <1){
            $("#userPlayerInB").html(noPlayersTxt);
        }else{
            $("#userPlayerInB").html('');
        }
        window.userTeam = window.teamAFBSelector.getselectedFriendIds();
    
    }else if(belongTeam == 2){
        $('.teamANameSpan').text(oppTeamTxt);
        $('.teamBNameSpan').text(myTeamTxt);
        $("#userPlayerInB").html('<img src="https://graph.facebook.com/'+window.user['fbId']+'/picture" /> ' +window.user['name']);
        if(window.teamAFBSelector.getselectedFriendIds().length <1){
            $("#userPlayerInA").html(noPlayersTxt);
        }else{
            $("#userPlayerInA").html('');
        }
        window.userTeam = window.teamBFBSelector.getselectedFriendIds();
    }else{
        $('.teamANameSpan').text(teamATxt);
        $('.teamBNameSpan').text(teamBTxt);

        if(window.teamAFBSelector.getselectedFriendIds().length <1){
            $("#userPlayerInA").html(noPlayersTxt);
        }else{
            $("#userPlayerInA").html('');
        }
        
        if(window.teamBFBSelector.getselectedFriendIds().length <1){
            $("#userPlayerInB").html(noPlayersTxt);
        }else{
            $("#userPlayerInB").html('');
        }
        window.userTeam = null;
    }
    window.belongTeam = belongTeam;
}


$('input[name=belongTeam]').change( function(){
   belongTeamChanged();
});

//Score validation

$(".score").keydown(function(event) {
        // Allow only backspace, delete, left and 
    if ( event.keyCode == 46 || event.keyCode == 8 ) {
        // let it happen, don't do anything
    }
    else {
        // Ensure that it is a number and stop the keypress
        if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault(); 
        }   
    }
});

var dateSelected = function(){
    window.isDateTimeSelected = true;
}
//Date picker
//, dateFormat: 'yy-mm-dd',timeFormat: 'hh:mm'
$('#matchDateTime').datetimepicker({ampm: true, onSelect: dateSelected,stepMinute: 5});

 
 
 $("#matchDateTime").keydown(function(event) {
    event.preventDefault();
 });
 
 //Auxiliar functions
var getFirstName = function(fullName){
    var splitName = jQuery.trim(fullName).split(" ");
    return splitName[0];
}