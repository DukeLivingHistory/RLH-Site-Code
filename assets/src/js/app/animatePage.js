/**
 * Handles page animation
 * {element}  wrapper   jQuery element 
 * {element}  page      Element to animate back in
 * {string}   dir       'left' or 'right'
 * {function} cb        Function to be called after animation
 */
var animatePage = function( wrapper, page, dir, cb ){
  var callback = cb || false;

  // Set min-height temporarily to prevent animation jank
  wrapper.css( 'min-height', '100vh' );
  
  // Add page
  wrapper.append( page );

  // If we have a provided direction and aren't explicitly ignoring it,
  // add and remove CSS classes to handle page animation
  if( dir && !IGNOREDIR ){
    page.addClass( 'pageTrans pageTrans--'+dir );
    setTimeout( function(){
      page.removeClass( 'pageTrans--'+dir );
    }, 20 );
    setTimeout( function(){
      page.removeClass( 'pageTrans' );
      if( callback ) callback();
    }, TRANSITIONTIME )
  } else {
    if( callback ) callback();
  }

  // Remove min height
  wrapper.css( 'min-height' );

}

module.exports = animatePage;
