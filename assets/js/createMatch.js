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
     window.sportBrandsJsonReq = jQuery.getJSON('/api/sport/brands?id='+window.selectedSportId, function(sponsors) {
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
    if(window.selectedSponsor!=null){

        console.log(window.selectedSponsor);

        console.log(window.selectedSportId);

        console.log(window.teamAFBSelector.getselectedFriendIds());
        
        console.log(window.teamBFBSelector.getselectedFriendIds());

    }else{
        alert('Select stuff first');
    }
    
    return false;

});


//For the Facebook Friends Selectors
window.fbAsyncInit = function () {

	FB.init({appId: window.appId, status: true, cookie: false, xfbml: false, oauth: true});

	$(document).ready(function () {
		var updatePlayersDiv, logActivity, callbackFriendSelected, callbackFriendUnselected, callbackMaxSelection, callbackSubmit;
        var callbackSubmitA, callbackSubmitB;
        
		// When a friend is selected, log their name and ID
//		callbackFriendSelected = function(friendId) {
//			var friend, name;
//			friend = TDFriendSelector.getFriendById(friendId);
//			name = friend.name;
//			logActivity('Selected ' + name + ' (ID: ' + friendId + ')');
//		};
//
//		// When a friend is deselected, log their name and ID
//		callbackFriendUnselected = function(friendId) {
//			var friend, name;
//			friend = TDFriendSelector.getFriendById(friendId);
//			name = friend.name;
//			logActivity('Unselected ' + name + ' (ID: ' + friendId + ')');
//		};

		// When the maximum selection is reached, log a message
//		callbackMaxSelection = function() {
//			logActivity('Selected the maximum number of friends');
//		};
//        
//        // When the user clicks OK, log a message
//		callbackSubmit = function(selectedFriendIds) {
//			logActivity('Clicked OK with the following friends selected: ' + selectedFriendIds.join(", "));
//		};
//        
		// When the user clicks OK, log a message
		callbackSubmitA = function(selectedFriendIds) {
            //console.log(selectedFriendIds);
			updatePlayersDiv(selectedFriendIds, 'teamAPlayersList');
            
            window.teamBFBSelector.setDisabledFriendIds(selectedFriendIds);
		};
        
        callbackSubmitB = function(selectedFriendIds) {
            //console.log(selectedFriendIds);
			updatePlayersDiv(selectedFriendIds, 'teamBPlayersList');
            window.teamAFBSelector.setDisabledFriendIds(selectedFriendIds);
		};

		// Initialise the Friend Selector with options that will apply to all instances
		TDFriendSelector.init({debug: true});

		// Create some Friend Selector instances
		window.teamAFBSelector  = TDFriendSelector.newInstance({
//			callbackFriendSelected   : callbackFriendSelected,
//			callbackFriendUnselected : callbackFriendUnselected,
//			callbackMaxSelection     : callbackMaxSelection,
			callbackSubmit           : callbackSubmitA,
            maxSelection             : 12,
			friendsPerPage           : 3
		});
        
		window.teamBFBSelector = TDFriendSelector.newInstance({
//			callbackFriendSelected   : callbackFriendSelected,
//			callbackFriendUnselected : callbackFriendUnselected,
//			callbackMaxSelection     : callbackMaxSelection,
			callbackSubmit           : callbackSubmitB,
			maxSelection             : 12,
			friendsPerPage           : 3,
			autoDeselection          : true
		});

//		FB.getLoginStatus(function(response) {
//			if (response.authResponse) {
//				$("#login-status").html("Logged in");
//			} else {
//				$("#login-status").html("Not logged in");
//			}
//		});

//		$("#btnLogin").click(function (e) {
//			e.preventDefault();
//			FB.login(function (response) {
//				if (response.authResponse) {
//					console.log("Logged in");
//					$("#login-status").html("Logged in");
//				} else {
//					console.log("Not logged in");
//					$("#login-status").html("Not logged in");
//				}
//			}, {});
//		});
//
//		$("#btnLogout").click(function (e) {
//			e.preventDefault();
//			FB.logout();
//			$("#login-status").html("Not logged in");
//		});

		$("#playersA").click(function (e) {
			e.preventDefault();
			window.teamAFBSelector.showFriendSelector();
		});

		$("#playersB").click(function (e) {
			e.preventDefault();
			window.teamBFBSelector.showFriendSelector();
		});
        
        updatePlayersDiv = function (playersIds, divId) {
            $("#"+divId).html('');
            for (i = 0, len = playersIds.length; i < len; i += 1) {
                $("#"+divId).append('<div class="playerInTeamList"><img src="https://graph.facebook.com/'+playersIds[i]+'/picture" /> ' +TDFriendSelector.getFriendById(playersIds[i])['name']+ '</div>');
                //console.log(TDFriendSelector.getFriendById(playersIds[i]));
            }
		};
        
		logActivity = function (message) {
			$("#results").append('<div>' + new Date() + ' - ' + message + '</div>');
		};
	});
};