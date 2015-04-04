var x = document.getElementById("selectEpisodeNumber");

for (epNum = 1; epNum <= 500; ++epNum) {
  var option = document.createElement("option");
  option.text = epNum;
  x.add(option);
}

$(document).ready(function(){
   var dialog = document.getElementById("myDialog")

   $("#dialogClose").click(function() {
   });


});

//DIALOG MESSAGE
function foo() {
 $( "#dialog-message" ).dialog({
   modal: false,
   buttons: {
     Ok: function() {
       $( this ).dialog( "close" );
     }
   }
 });
}

$(".time").click(function() {
   foo();
   alert($(this).position());
   //$(this).val("return value");
});


$("#idInputNewBookmark").click(function() {
   alert("noob");
   $('#tableBookmark tr:last').after('<tr>hitr><tr>bye</tr>');
   //$(this).val("return value");
});
