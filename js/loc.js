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
LG.loc._navigator = function (callback, elsecallback) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (geo) {
      callback(new GM.LatLng(geo.coords.latitude, geo.coords.longitude));
    }, elsecallback);
  } else {
    elsecallback();
  }
};
LG.loc._gears = function (callback, elsecallback) {
  if (google.gears) {
    var geo = google.gears.factory.create('beta.geolocation');
    geo.getCurrentPosition(function(position) {
      callback(new GM.LatLng(position.latitude,position.longitude));
    }, elsecallback);
  } else {
    elsecallback();
  }
};
LG.loc._clientloc = function (callback, elsecallback) {
  if (google.loader && google.loader.ClientLocation) {
    var coord = google.loader.ClientLocation;
    if (coord) {
      callback(new GM.LatLng(coord.latitude, coord.longitude));
    } else {
      elsecallback();
    }
  } else {
    elsecallback();
  }
};
LG.loc.get = function (callback) {
  /* Calls the callback with the browser's current location as a google.maps.
     LatLng.
     The location is based on W3C Geolocation, falls back to Google Gears, then 
     tries google client location, and then gives up.
  */
  LG.loc._navigator(callback, function () {
    LG.loc._gears(callback, function () {
      callback(null);
    });
  });
};
