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
var sponsors;
var selectedSponsor;
var sponsorPicsFolder;
var sponsorBannersFolder;

sponsorPicsFolder='/assets/images/sponsors/';
sponsorBannersFolder=sponsorPicsFolder+'banners/';

updatePlayersDiv = function (){
    jQuery.getJSON('/api/brand/sport?id='+window.selectedSportId, function(data) {
      window.sponsors = data;
      $('#sponsors-list').text('');
      console.log (data);

    //  for (i = 0, len = data.length; i < len; i += 1) {
    //        $("#sponsors-list").append('<div class="sponsor-item"><img src="'+sponsorBannersFolder+'" /> ' +TDFriendSelector.getFriendById(playersIds[i])['name']+ '</div>');
    //        console.log(TDFriendSelector.getFriendById(playersIds[i]));
    //    }

    });
};

updatePlayersDiv();

$("#sportSelect").change(function() {
    window.selectedSportId = this.value;
    updatePlayersDiv();
});





//For the Facebook Friends Selectors
window.fbAsyncInit = function () {

	FB.init({appId: window.appId, status: true, cookie: false, xfbml: false, oauth: true});

	$(document).ready(function () {
		var selector1, selector2, updatePlayersDiv, logActivity, callbackFriendSelected, callbackFriendUnselected, callbackMaxSelection, callbackSubmit;
        var callbackSubmit1, callbackSubmit2;
        
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
		callbackSubmit1 = function(selectedFriendIds) {
            console.log(selectedFriendIds);
			updatePlayersDiv(selectedFriendIds, 'teamAPlayersList');
		};
        
        callbackSubmit2 = function(selectedFriendIds) {
            console.log(selectedFriendIds);
			updatePlayersDiv(selectedFriendIds, 'teamBPlayersList');
		};

		// Initialise the Friend Selector with options that will apply to all instances
		TDFriendSelector.init({debug: true});

		// Create some Friend Selector instances
		selector1 = TDFriendSelector.newInstance({
//			callbackFriendSelected   : callbackFriendSelected,
//			callbackFriendUnselected : callbackFriendUnselected,
//			callbackMaxSelection     : callbackMaxSelection,
			callbackSubmit           : callbackSubmit1,
            maxSelection             : 12,
			friendsPerPage           : 3
		});
        
		selector2 = TDFriendSelector.newInstance({
//			callbackFriendSelected   : callbackFriendSelected,
//			callbackFriendUnselected : callbackFriendUnselected,
//			callbackMaxSelection     : callbackMaxSelection,
			callbackSubmit           : callbackSubmit2,
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
			selector1.showFriendSelector();
		});

		$("#playersB").click(function (e) {
			e.preventDefault();
			selector2.showFriendSelector();
		});
        
        updatePlayersDiv = function (playersIds, divId) {
            $("#"+divId).html('');
            for (i = 0, len = playersIds.length; i < len; i += 1) {
                $("#"+divId).append('<div class="playerInTeamList"><img src="https://graph.facebook.com/'+playersIds[i]+'/picture" /> ' +TDFriendSelector.getFriendById(playersIds[i])['name']+ '</div>');
                console.log(TDFriendSelector.getFriendById(playersIds[i]));
            }
		};
        
		logActivity = function (message) {
			$("#results").append('<div>' + new Date() + ' - ' + message + '</div>');
		};
	});
};