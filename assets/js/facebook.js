/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

window.fbAsyncInit = function() {
    FB.init({ appId: '194277843976863',
        status: true,
        cookie: true,
        xfbml: true,
        oauth: true});
};

(function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol
        + '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
}());

function login(response, info){
    if (response.authResponse) {
        var accessToken                                =   response.authResponse.accessToken;
 
        userInfo.innerHTML                             = '<img src="https://graph.facebook.com/' + info.id + '/picture">' + info.name
            + "<br /> Your Access Token: " + accessToken;
        button.innerHTML                               = 'Logout';
        showLoader(false);
        document.getElementById('other').style.display = "block";
    }
}
 
function logout(response){
    userInfo.innerHTML                             =   "";
    document.getElementById('debug').innerHTML     =   "";
    document.getElementById('other').style.display =   "none";
    showLoader(false);
}