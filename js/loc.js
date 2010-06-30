// Adapted from http://code.google.com/apis/maps/documentation/javascript/
//   basics.html#DetectingUserLocation 
// Note that using Google Gears requires loading the Javascript
// at http://code.google.com/apis/gears/gears_init.js
var GM = google.maps;
LG.loc = {
  msg: {
    FAILED: 'LG.loc: Unable to geolocate browser.',
  },
  waitLimit: 100000
};
LG.loc.get = function (callback) {
  /* Calls the callback with the browser's current location as a google.maps.
     LatLng.
     The location is based on W3C Geolocation, falls back to Google Gears, then 
     gives up.
  */
  if (navigator.geolocation) {
    // Try W3C Geolocation (Preferred)
    navigator.geolocation.getCurrentPosition(function (position) {
      callback(new GM.LatLng(position.coords.latitude, position.coords.longitude));
    }, function () {
      if (google.gears) {
        // Try Google Gears Geolocation
        var geo = google.gears.factory.create('beta.geolocation');
        geo.getCurrentPosition(function(position) {
          callback(new GM.LatLng(position.latitude,position.longitude));
        }, function () {
          callback(null);
        });
      }
    });
  }
};
