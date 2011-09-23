$(document).ready(function(){
    $("#signup-email").keyup(function(){
        if($("#signup-email").val().length >= 4)
        {
        $.ajax({
            type: "POST",
            url: "signup/check/duplicateEmail",
            data: "email="+$("#signup-email").val(),
            success: function(msg){
                if(msg=="true")
                {
                    $("#email_verify").css({ "background-image": "url('<?php echo base_url();?>images/yes.png')" });
                }
                else
                {
                    $("#email_verify").css({ "background-image": "url('<?php echo base_url();?>images/no.png')" });
                }
            }
        });
        }
        else
        {
            $("#email_verify").css({ "background-image": "none" });
        }
    });
});