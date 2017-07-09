var gotovKompajler = false;
var brTocki = 0;

$(document).ready( function() {

$("textarea").on("keydown", function(event)
{
  switch(event.key)
  {
    // Na tab press radi ono što se očekuje
    case "Tab":
      // Zabrani da na pritisak taba prebaci fokus na drugi element (tj. ono što radi po defaultu)
      event.preventDefault();

      var start = $(this).prop("selectionStart");
      var end = $(this).prop("selectionEnd");

      // Postavi vrijednosti u textboxu na "početak-oznaka + tab + oznaka-kraj"
      $(this).val($(this).val().substring(0, start)
                  + "\t"
                  + $(this).val().substring(end));

      // Pomakni pointer na lokaciju poslije taba
      $(this).prop("selectionStart", start + 1);
      $(this).prop("selectionEnd",   start + 1);
      break;

    //Enter nakon '{' nadodaje tab u novom redu
    case "Enter":
      console.log("Tipka "+$(this).val().substring( $(this).prop("selectionStart") - 1 , $(this).prop("selectionStart") ));
      if( $(this).val().substring( $(this).prop("selectionStart") - 1 , $(this).prop("selectionStart") ) != "{" )
        break;

      // Onemogući prijelaz u novi red
      event.preventDefault();

      // Postavi vrijednosti u textboxu na "početak-oznaka + prijelaz u novi red + tab + oznaka-kraj"
      $(this).val($(this).val().substring(0, $(this).prop("selectionStart"))
                  + "\n\t"
                  + $(this).val().substring($(this).prop("selectionEnd")));

      // Pomakni pointer na lokaciju poslije taba
      $(this).prop("selectionStart", $(this).prop("selectionStart") + 1);
      $(this).prop("selectionEnd",   $(this).prop("selectionEnd") + 1);
      break;
  }
});

$("button.posaljiZadatak").on("click", function(event){
  console.log($("form").prop("action"));
  console.log($("textarea.kod").val());

  //Ispisuje radim sa različitom količinom točki da pokaže korisniku da se kod kompajlira i izvršava
  var interval = setInterval(function(){
    if(gotovKompajler)
    {
      gotovKompajler = false;
      clearInterval(interval);
    }
    else
    {
      var tocke = '.';
      for(i = 0; i < brTocki; ++i)
        tocke += '.';
      $(".log").html("Radim" + tocke);
      brTocki = (brTocki + 1) % 3;
    }
  }, 200);

  $.ajax(
  {
      url: $("form").prop("action"),
      data:
      {
          kod: $("textarea.kod").val()
      },
      type: "POST",
      success: function( data )
      {
          // Jednostavno sve što dobiješ od servera stavi u dataset.
          var output = data;
          console.log(data);
          gotovKompajler = true;
          $( ".log" ).html( data );

      },
      error: function( xhr, status )
      {
          if( status !== null )
              console.log( "Greška prilikom Ajax poziva: " + status );
          gotovKompajler = true;
          $(".log").html("Greška pri komunikaciji sa kompajlerom!");
      }
  } );

});


  } );
