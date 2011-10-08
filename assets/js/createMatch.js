/*jslint devel: true, bitwise: false, undef: false, browser: true, continue: false, debug: false, eqeq: false, es5: false, type: false, evil: false, vars: false, forin: false, white: true, newcap: false, nomen: true, plusplus: false, regexp: true, sloppy: true */
/*globals $, jQuery, FB, TDFriendSelector */
(function () {
	var e = document.createElement('script');
	e.async = true;
	e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);
}());


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


$('#submitMatch').click(function() {
    var errors = [];
    
    //Validation
    if(window.selectedSponsor==null){
        errors.push( {type: 'sponsor', value: true});
    }
    
    if(window.teamAFBSelector.getselectedFriendIds().length <1){
         errors.push( {type: 'teamA', value: true});
    }
    
    if(window.teamBFBSelector.getselectedFriendIds().length <1){
         errors.push( {type: 'teamB', value: true});
    }
    
    //console.log(errors);
    console.log("Errors: "+ errors.length);
    console.log(errors);
    if(errors.length<1){
        
        var teamAFBPlayers = window.teamAFBSelector.getselectedFriendIds().slice(0);
        var teamBFBPlayers = window.teamBFBSelector.getselectedFriendIds().slice(0);
                
        var currentDate = new Date();
        var title = window.selectedSponsor.id +" " + window.selectedSportId + " match: " +window.scoreA +" - "+window.scoreB;
        
        var csrf = $('#csrf_protection').val();
        
        var belongTeam = $('input[name=belongTeam]:checked', '#newMatchForm').val();

        params = {
            "accessToken" : null,
            "sportId" : window.selectedSportId,
            "brandId": window.selectedSponsor.id ,
            "title" : null,
            //@"1", @"leagueType",
            "scoreA" : window.scoreA,
            "scoreB" : window.scoreB,
            //@"", @"memberIdsA",
            "memberFbIdsA[]" : teamAFBPlayers,
            //@"", @"memberIdsB",
            "memberFbIdsB[]" : teamBFBPlayers,
            "started" : currentDate.getTime(),
            "csrf_protection": csrf ,
            "ended" : currentDate.getTime()
        };

        //Make AJAX POST
        submitMatch = $.post(baseSSLUrl+'api/match', params);
        
        //Show success message and post to FB
        submitMatch.success( function(){
             
             function postToWallUsingFBApi()
            {
                var sponsorShareIcon = window.sponsorShareIconsFolder+sportsList[selectedSportId].stringId+window.selectedSponsor.stringId +'_shareicon'+'.gif';
                
                var message = window.user['fullName'];
                
                
                var name = selectedSponsor.name +" "+sportsList[selectedSportId].stringId+ "Match";
                    
                var data=
                {
                    message: "Great Game!",
                    //display: 'iframe',
                    caption: "An amazing game",
                    name: name,  
                    picture: sponsorShareIcon,    
                    link: "http://www.stadioom.com/",  // Go here if user click the picture
                    description: "Description field",
                    actions: [{ name: 'Join the stadioom and join the sports fun!', link: 'http://www.stadioom.com' }]			
                }
                //console.log(data);    
                FB.api('/me/feed', 'post', data, onPostToWallCompleted);
                
            }
        });


//   NSString *caption = [NSString stringWithFormat:@"%@ just defeated %@ in a fierce %@ match.", namesInTeamA, namesInTeamB, self.game.name];
//    NSString *message = [NSString stringWithFormat:@"%@ won!", ([self.teamMembers count] > 0)? @"We" : @"I"];
//    NSString *picture = [NSString stringWithFormat:@"http://stadioom.com/assets/images/sponsors/shareicons/%@_%@_shareicon.gif", self.sponsor.name, self.game.name];
//    NSString *name = [NSString stringWithFormat:@"%@ %@ Match", self.sponsor.name, self.game.name];
//    NSString *link = [NSString stringWithFormat:@"http://stadioom.com/match/view/%@", self.matchId];
//    NSString *description = [NSString stringWithFormat:@"Final score: %@ %@ - %@ %@", namesInTeamA, self.myScore, namesInTeamB, self.opponentScore];
//        
        return false;
    }
    
    var onPostToWallCompleted = function(){
        alert('posted to FB');
    }
    
    
    return false;

});