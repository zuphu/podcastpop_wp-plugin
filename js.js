var x = document.getElementById("selectEpisodeNumber");

//Preload the episode number from 1 to 500
for (epNum = 1; epNum <= 500; ++epNum) {
    var option = document.createElement("option");
    option.value = epNum;
    option.text = 'Episode' + " " + epNum;
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

$(function () {
  // var image = '<img src="http://4.bp.blogspot.com/-JOqxgp-ZWe0/U3BtyEQlEiI/AAAAAAAAOfg/Doq6Q2MwIKA/s1600/google-logo-874x288.png">';

  // $('[data-toggle="tooltip"]').tooltip({ html: true });

  $('[data-toggle="tooltip"]').tooltip({ trigger: "manual" , html: true, animation:false})
.on("mouseenter", function () {
    var _this = this;
    $(this).popover("show");
    $(".popover").on("mouseleave", function () {
        $(_this).popover('hide');
    });
}).on("mouseleave", function () {
    var _this = this;
    setTimeout(function () {
        if (!$(".popover:hover").length) {
            $(_this).popover("hide");
        }
    }, 300);
});

  
})
// $(document).ready(function(){
//     $("#inputHour").spinner();
//     $("#inputMinute").spinner();
//     $("#inputSecond").spinner();
// });

/*
$(".time").click(function(e) {
    var currentTime = $(this).val();
    var splitTime = currentTime.split(":");
    var hour = splitTime[0];

    var minute = splitTime[1];
    var second = splitTime[2];


    if (!currentTime == "") {
	$("#inputHour").val(hour);
	$("#inputMinute").val(minute);
	$("#inputSecond").val(second);
    }
    else
    {
	$("#inputHour").val();
	$("#inputMinute").val();
	$("#inputSecond").val();
    }
    var id=$(this).attr('id');

    $( "#dialog-message" ).dialog({
	modal: false,
	buttons: {
	    Ok: function() {
		hour = parseInt($("#inputHour").val());
		minute = parseInt($("#inputMinute").val());
		second = parseInt($("#inputSecond").val());
		if ( isNaN(hour) ) {
    		    $(errorHour).html("Error: enter a number");
		}
		if ( isNaN(minute) ) {
		    $(errorMinute).html("Error: enter a number");
		}
		if ( isNaN(second) ) {
		    $(errorSecond).html("Error: enter a number");
		}
		if (isNaN(hour) || isNaN(minute) || isNaN(second))
		    return;

		var time = hour + ":" + minute + ":" + second;
		$("#" + id).val(time);
		$("#errorHour").html("")
		$("#errorMinute").html("")
		$("#errorSecond").html("")

		$( this ).dialog( "close" );
	    }
	},
	position: {
            my: "right top",
            at: "left top",
            of: $(this),
	    collision: "flipfit"
	}

    });

    $("#dialog-message").dialog("open");
});
*/

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

function validateBookMark() { 
   if (validateTime() && validateBookmarkText())
      postForm();
}

function validateTime () {
   var time = $('#inputTime').val();
   var colon = time.split(":").length - 1;
   var regex = /^\d{1,2}:\d{1,2}(:\d{1,2})?$/;
   if (regex.test(time) && (colon == 1 || colon == 2))
      return true;
   else {
      alert("Please enter a time in the format 'hh:mm:ss'.");
      $("#inputTime").focus();
      return false;
   }
}

function validateBookmarkText() {
   var text = $('#idInputBookmarkText').val();
   if (text.trim()) {
      return true;
   }
   else {
    alert("Please enter text for the bookmark");
    $("#idInputBookmarkText").focus();
    return false;
   }       
}

function validateTitle () {
$("#")

}

function postForm() {
  $("#inputForm").submit();
}


$(".deleteButton").click(function (event) {
  var result = confirm("Are you sure you want to remove this bookmark?");
  if (result) {
    $(this).attr("type", "submit");
    $("#deleteForm").submit();
  }
  else {
    return;
  }
});
