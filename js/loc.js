// Adapted from http://code.google.com/apis/maps/documentation/javascript/
//   basics.html#DetectingUserLocation 
// Note that using Google Gears requires loading the Javascript
// at http://code.google.com/apis/gears/gears_init.js
LG.loc = {};
LG.loc.get = function () {
  /* Gives the browser's current location.
     The location is based on W3C Geolocation, falls back to Google Gears, then 
     gives up. If the location is not determined, returns null.
     Return: browser's current location (GM.LatLng)
  */
  const GM = google.maps;
  const _MSG_FAILED = 'LG.loc: Geolocation service failed';
  const _MSG_UNSUPPORTED = "LG.loc: Browser doesn't support geolocation.";

  if (navigator.geolocation) {
    // Try W3C Geolocation (Preferred)
    navigator.geolocation.getCurrentPosition(function(position) {
      return new GM.LatLng(position.coords.latitude, position.coords.longitude);
    }, function () {
      console.log(_MSG_FAILED);
    });
  } else if (google.gears) {
    // Try Google Gears Geolocation
    const geo = google.gears.factory.create('beta.geolocation');
    var queryReady = false;
    var loc = null;
    geo.getCurrentPosition(function(position) {
      loc = new GM.LatLng(position.latitude,position.longitude);
      queryReady = true;
    }, function () {
      console.log(_MSG_FAILED);
      queryReady = true;
    });
    waitUntil(function () { return queryReady; });
    return loc;
  } else {
    // Browser doesn't support Geolocation
    console.log(_MSG_UNSUPPORTED);
  }
  return null;
};
