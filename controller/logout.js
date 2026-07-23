$(document).ready(function(){
    $("#logoutBtn").click(function(){
        $.ajax({
            url: "logout.php",
            type: "POST",
            success: function(){
                             //redirect to login page after logout
                            window.location.href= "login.php";
            },
            error: function(){
                alert("logout failed");
            }
        });
    });
});