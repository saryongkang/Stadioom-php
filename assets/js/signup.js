
var ck_name = /^[A-Za-z0-9 ]{3,20}$/;
var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
var ck_username = /^[A-Za-z0-9_]{1,20}$/;
var ck_password =  /^[A-Za-z0-9!@#$%^&*()_]{6,20}$/;


$(document).ready(function(){
    $("#signup-email").keyup(function(){
        if($("#signup-email").val().length >= 6){
            if ( ck_email.test($("#signup-email").val() ) ) {

                $.ajax({
                    type: "POST",
                    url: "signup/check/duplicateEmail",
                    data: "email="+$("#signup-email").val(),
                    success: function(msg){
                        if(msg=="true")
                        {
                            generateFieldError('email', 'Already registered!');

                        }
                        else
                        {
                            //$("#email_verify").css({ "background-image": "url('assets/images/no.png')" });
                            removeFieldError('email', 'ok!');
                        }
                    }
                });
            }else{
                generateFieldError('email', 'invalid e-mail');
            }
        }
        else
        {
            removeFieldError('email', '');
        }
    });
});

function generateFieldError(elementName, msg){
    $("#clearfix-"+elementName).addClass("error");
    $("#signup-"+elementName).addClass("error");
    $("#help-"+elementName).text(msg);
}

function removeFieldError(elementName, msg){
    $("#clearfix-"+elementName).removeClass("error");
    $("#signup-"+elementName).removeClass("error");
    $("#help-"+elementName).text(msg);
}