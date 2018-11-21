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
    message = $("#message_text").val();
    if($.trim(message) == '') {
        return false;
    }
    url = $('#ajax-url').data();

        $.ajax({
            url: 'message/add',
            method: "POST",
            dataType: "json",
            data: {
                "text": message
            },
            success: function (data)
            {
                $('<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" /><span>' + username + '</span><p>' + message + '</p></li>').appendTo($('.messages ul'));
                $('.message-input input').val(null);
                $('.contact.active .preview').html('<span>You: </span>' + message);
                $(".messages").animate({ scrollTop: $(document).height() }, "fast");
                $("#message_text").val("");

            }
        });
        return false;
};

$('.submit').click(function() {
    newMessage();
});

$(window).on('keydown', function(e) {
    if (e.which == 13) {
        newMessage();
        return false;
    }
});

function addGroupe(e){
    e.preventDefault();
    var nameGroupe = $('#groupe input').val();

    if(nameGroupe == ""){
        return false;
    }

    var url = $('#groupe_ajax_url').data();

    $.ajax({
        url: 'message/addgroupe',
        dataType: "json",
        data: {
            "name": nameGroupe
        },
        method: "post",
    })
    .done(function (groupes) {
        console.log("deee");
        groupes.forEach(function (groupe) {

            $("#dropdown_groupe").append('<p class="dropdown-item"'> + groupe.name + '</p>');
        })
    });
};

$("form[name='groupe_add']").on("submit", addGroupe);


$('#view_groupe_ajax_url').on('click', '.lien_groupe', function(e){
    e.preventDefault();
    var link = $(this).attr('href');

    selectGroupe(link);
});

// $('#submit_add_groupe').click(function(){
//     console.log('ee');
//     addUserGroupe();
// })

function viewGroupe(){
    $.ajax({
        url: 'message/viewgroupe',
        dataType: "json",
        method: "get",
    })
    .done(function (groupes) {
        groupes.forEach(function (groupe) {
            $("#dropdown_groupe").append('<p class="dropdown-item"><a class="lien_groupe" href="'+ pathGroupe.replace("0", groupe.id)+'">' + groupe.name + '</a></p>');
        })
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

            $("#groupe_profil").html('<span>' + groupes.name + '</span>');
    });
}

// function addUserGroupe(){
//
//     $.ajax({
//         url: 'message/add-user-groupe',
//         dataType: "json",
//         method: "post",
//         data: {
//             "iduser": message
//         },
//     })
//         .done();
// }
