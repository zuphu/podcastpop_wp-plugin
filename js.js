var x = document.getElementById("selectEpisodeNumber");

//Preload the episode number from 1 to 500
for (epNum = 1; epNum <= 500; ++epNum) {
  var option = document.createElement("option");
  option.value = epNum;
  option.text = epNum;
    x.add(option);
}

var episodeNumber = getCookie("episodeNumber");
console.log(episodeNumber);
if ( isNaN(episodeNumber) ) {
   $("#selectEpisodeNumber").val("default");
}
else {
   $("#selectEpisodeNumber").val(episodeNumber);
}

$(document).ready(function(){

});

$(".time").click(function(e) {
    console.log($(this).width());
    console.log($(this).height());

    $( "#dialog-message" ).dialog({
	modal: false,
	buttons: {
	    Ok: function() {
		$( this ).dialog( "close" );
	    }
	},
	position: $(this).position()
    });

    $("#dialog-message").dialog("open");
});



$("#idInputNewBookmark").click(function() {
   //$(this).prop('disabled', true);
   //$('#tableBookmark tr:last').after('<tr>hitr><tr>bye</tr>');
   //$(this).val("return value");
});

$('#selectEpisodeNumber').change(function(){
    var episodeNumber = $(this).val();
    document.cookie="episodeNumber=" + episodeNumber; 
   $("#inputForm").submit();
});

$("#inputSaveTitle").click(function() {
    console.log("yea");
});

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
