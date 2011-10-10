//For the Facebook Friends Selectors

//function fbLoginStatus(response) {
//     if(response.session) {
//        //user is logged in, display profile div
//     } else {
//        //user is not logged in, display guest div
//        window.location = "https://stadioomtest.com/fb/session/logout"
//     }
//  }

window.fbAsyncInit = function () {

	FB.init({appId: window.appId, status: true, cookie: false, xfbml: false, oauth: true});
//    FB.getLoginStatus(fbLoginStatus);
//    FB.Event.subscribe('auth.statusChange', fbLoginStatus);

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
		TDFriendSelector.init({debug: false});

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