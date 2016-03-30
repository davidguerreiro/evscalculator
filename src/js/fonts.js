;(function( doc ) {
  // Clear localFont
  localStorage.clear();

  var font_primary = 'Cabin';
  var font_secondary = 'Montserrat';

  // Primary fonts
  var Cabin = new FontFaceObserver(font_primary);
  var CabinBold = new FontFaceObserver(font_primary, {
    weight: 600
  });
  var CabinItalic = new FontFaceObserver(font_primary, {
    style: 'italic'
  });

  // Fallback font
  var Montserrat = new FontFaceObserver(font_secondary);
  var MontserratBold = new FontFaceObserver(font_secondary,{
    weight: 700
  });

  function fontsHaveLoaded(){
    sessionStorage.fontsLoaded = true;
    doc.documentElement.className += " f2";
  }

  Promise.all([CrimsonText.check(null,0), CrimsonTextBold.check(null,0), CrimsonTextItalic.check(null,0)]).then(function () {
    doc.documentElement.className += " f1";
 
      Promise.all([Montserrat.check(null,0), MontserratBold.check(null,0)]).then(fontsHaveLoaded);  
  });
  
})( document );
