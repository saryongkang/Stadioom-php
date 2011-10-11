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


sportsChanged= function() {
    disableSponsorSelect();
    
    window.selectedSportId = $("#sportSelect").val();
    
    sportBrandsJson = window.updateSponsorsDiv();
    
    sportBrandsJson.complete(function() {
        window.reEnableSponsorSelect();
        window.sportBrandsJsonReq = null;
    });
}

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
    
    window.scoreA = $('#scoreA').val();
    window.scoreB = $('#scoreB').val();
    
    //Validation
    if(!window.scoreA){
        errors.push( {type: 'scoreA', value: true});
        $("#scoreAErrorDiv").fadeIn();
    }else{
        //TODO: code for validation
    }
    
    if(window.scoreB==null){
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
         errors.push( {type: 'teamA', value: true});
         $("#teamAErrorDiv").fadeIn();
    }
    
    if(window.teamBFBSelector.getselectedFriendIds().length <1){
         errors.push( {type: 'teamB', value: true});
         $("#teamBErrorDiv").fadeIn();
    }
    
    //console.log(errors);
    //console.log("Errors: "+ errors.length);

    if(errors.length<1){
        
        var teamAFBPlayers = window.teamAFBSelector.getselectedFriendIds().slice(0);
        var teamBFBPlayers = window.teamBFBSelector.getselectedFriendIds().slice(0);
                
        var currentDate = new Date();
        var title = window.selectedSponsor.id +" " + window.selectedSportId + " match: " +window.scoreA +" - "+window.scoreB;
        
        var cct = $.cookie('safe_sdsk_stad');
        
        var belongTeam = $('input[name=belongTeam]:checked', '#newMatchForm').val();
        //console.log(belongTeam);
        
        if(belongTeam == 1){
            teamAFBPlayers.push(window.user['fbId']);
        }else if(belongTeam == 2){
            teamBFBPlayers.push(window.user['fbId']);
        }
        console.log(window.user['fbId']);
        console.log('ScoreA ='+window.scoreA + " " + 'ScoreB ='+window.scoreB);
        console.log('Belongteam ='+belongTeam);
        
        params = {
            "sportId" : window.selectedSportId,
            "brandId": window.selectedSponsor.id ,
            "title" : null,
            //@"1", @"leagueType",
            "userId" : window.user['id'],
            "scoreA" : window.scoreA,
            "scoreB" : window.scoreB,
            //@"", @"memberIdsA",
            "memberFbIdsA[]" : teamAFBPlayers,
            //@"", @"memberIdsB",
            "memberFbIdsB[]" : teamBFBPlayers,
            "started" : currentDate.getTime(),
            "sdsk_stad_tok": cct ,
            "ended" : currentDate.getTime()
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

        });


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
    
    var postToWallUsingFBApi = function(){
        var sponsorShareIcon = window.baseUrl+window.sponsorShareIconsFolder+window.selectedSponsor.stringId +'_'+sportsList[selectedSportId].stringId+'_shareicon'+'.gif';

        var message = window.user['fullName'];


        var name = selectedSponsor.name +" "+sportsList[selectedSportId].stringId+ " Match";

        var data=
        {
            message: "Great Game!",
            //display: 'iframe',
            caption: "An amazing game",
            name: name,  
            picture: sponsorShareIcon,    
            link: window.baseUrl,  // Go here if user click the picture
            description: "Description field",
            actions: [{ name: 'Enter the Stadioom', link: window.baseUrl }]			
        }
        //console.log(data);    
        FB.api('/me/feed', 'post', data, onPostToWallCompleted);
        $("#fbShareSuccess").fadeIn();
    }


}); //End of Submit Match


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


//Date picker


$(function(){
    $('#matchDate').datepicker({ defaultDate: +0 });
    //For Date RANGE
//    $('#matchDate').daterangepicker({arrows:true}); 
 });
