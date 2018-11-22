$( document ).ready(function() {
    viewGroupe()
});

$(".messages").animate({ scrollTop: $(document).height() }, "fast");

$("#profile-img").click(function() {
    $("#status-options").toggleClass("active");
});

$(".expand-button").click(function() {
    $("#profile").toggleClass("expanded");
    $("#contacts").toggleClass("expanded");
});

$("#status-options ul li").click(function() {
    $("#profile-img").removeClass();
    $("#status-online").removeClass("active");
    $("#status-away").removeClass("active");
    $("#status-busy").removeClass("active");
    $("#status-offline").removeClass("active");
    $(this).addClass("active");

    if($("#status-online").hasClass("active")) {
        $("#profile-img").addClass("online");
    } else if ($("#status-away").hasClass("active")) {
        $("#profile-img").addClass("away");
    } else if ($("#status-busy").hasClass("active")) {
        $("#profile-img").addClass("busy");
    } else if ($("#status-offline").hasClass("active")) {
        $("#profile-img").addClass("offline");
    } else {
        $("#profile-img").removeClass();
    };

    $("#status-options").removeClass("active");
});

username = $('#username span').html();


function newMessage() {

    var idGroupe = $("#groupe_profil").attr('data-id');
    message = $("#message_text").val();

        $.ajax({
            url: 'message/add',
            method: "POST",
            dataType: "json",
            data: {
                "text": message,
                "idGroupe": idGroupe
            },
            success: function (data)
            {
                console.log(data);
                $('#list_message ul:last-child').append('<li class="sent"> <p id="author">' + data.author + ' dit : </p> <p id="message_text_list" data-id="'+data.id+'">' + data.message + '</p> <p id="date_message"> à ' + data.date.date +'</p> </li>')

            }
        });
        return false;
};


$('#message_text').keyup(function(e) {
    if(e.keyCode == 13) {

        newMessage();
        $('#message textarea').val('');
    }

});

//Ajout d'un groupe à User
$("form[name='groupe_add']").on("submit", addGroupe);

function addGroupe(e) {

    e.preventDefault();

    var nameGroupe = $('#groupe_add_name').val();

    if (nameGroupe == "") {
        return false;
    }

    $.ajax({
        url: 'message/addgroupe',
        dataType: "json",
        type: "POST",
        data: {
            "name": nameGroupe
        },

        success: function (data) {

            $("#dropdown_groupe").children().remove();
            viewGroupe();
        }
    });
}



$('#view_groupe_ajax_url').on('click', '.lien_groupe', function(e){
    e.preventDefault();
    var link = $(this).attr('href');

    selectGroupe(link);

});


function viewGroupe() {
    $.ajax({
        url: 'message/viewgroupe',
        dataType: "json",
        type: "GET"
        ,
        success: function (user) {
            user.groupes.forEach(function (groupe) {

                $("#dropdown_groupe").append('<p class="dropdown-item"><a class="lien_groupe" href="' + pathGroupe.replace("0", groupe.id) + '">' + groupe.name + '</a></p>');
            })
        }
    });
}

function updateGroupe() {
    var objectIdMessage = $('#list_message li:last-child').find('#message_text_list').data();
    var lastIdMessage = objectIdMessage.id;
    var idGroupe = $("#groupe_profil").attr('data-id');

    $.ajax({
        url: 'message/update_message_groupe',
        dataType: "json",
        type: "POST",
        data: {
            "lastIdMessage": lastIdMessage,
            "idGroupe": idGroupe
        },

        success: function (groupe) {

            groupe.messages.forEach(function (message){
                $('#list_message').children().append('<li class="sent"> <p id="author">' + message.author + ' dit : </p> <p id="message_text_list" data-id="'+message.id+'">' + message.message + '</p> <p id="date_message"> à ' + message.date.date +'</p> </li>')
            })
        }
    });
}


function selectGroupe(link){

    var regex = /(\d*)$/;
    var match = regex.exec(link);
    var id = match[1];

    $.ajax({
        url: 'message/select-groupe_' + id,
        dataType: "json",
        method: "get",
    })
    .done(function (groupes) {
        $("#groupe_profil").html(groupes.name);
        $("#groupe_profil").attr('data-id', groupes.id);
        viewUserGroupe();
        viewMessage();
    });

}

$('#submit_add_groupe').on('click', function(e){
    e.preventDefault();

    //Parcour chaque élément et éxécute la fonction pour chaque
    var tabId = $.map($("input[name='groupe_add_user[user][]']:checked"), function(v,i) {
        return +v.value;
    });

    addUserGroupe(tabId);
    viewUserGroupe();
});


function addUserGroupe(tabId){

    var idGroupe = $("#groupe_profil").attr('data-id');

    $.ajax({
        url: 'message/add-user-groupe',
        dataType: "json",
        type: "POST",
        data: {
            "idUser": tabId,
            "idGroupe": idGroupe,
        },

        success: function () {
            viewUserGroupe();
        }
    });
}

function viewUserGroupe(){

    var idGroupe = $("#groupe_profil").attr('data-id');

    $.ajax({
        url: 'message/view-user-groupe',
        dataType: "json",
        type: "POST",
        data: {
            "idGroupe": idGroupe,
        },

        success: function (groupe) {
            $("#block_invite ul").children().remove();
            groupe.userName.forEach(function (username) {
                $("#block_invite").children('ul').append('<li><p>' + username + '</p></li>');
            })
        }
    });
}

function viewMessage(){

    var idGroupe = $("#groupe_profil").attr('data-id');

    $.ajax({
        url: 'message/viewMessage',
        dataType: "json",
        type: "POST",
        data: {
            "idGroupe": idGroupe,
        },

        success: function (groupe) {
            console.log(groupe);
            groupe.messages.forEach(function (message){
                $('#list_message').children().append('<li class="sent"> <p id="author">' + message.author + ' dit : </p> <p id="message_text_list" data-id="'+message.id+'">' + message.message + '</p> <p id="date_message"> à ' + message.date.date +'</p> </li>')
            })

        }
    });
}

setInterval(function(){
    var idGroupe = $("#groupe_profil").attr('data-id');

    if(idGroupe == undefined){

    }else{

        updateGroupe();
    }
}, 5000);
