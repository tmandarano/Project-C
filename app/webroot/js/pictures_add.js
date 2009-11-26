var PROJC = PROJC ? PROJC : {};
PROJC.PICTURES = {};
PROJC.PICTURES.ADD = {};
PROJC.PICTURES.ADD.init = function() {
  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    disableDefaultUI: true,
    mapTypeControl: false,
    navigationControl: true,
    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL}
  };
  var map = PROJC.PICTURES.ADD.map = new google.maps.Map($("#map")[0], mapOpts);
  PROJC.PICTURES.ADD.marker = new google.maps.Marker({map: map});
  google.maps.event.addListener(map, 'click', function(e) {
    PROJC.PICTURES.ADD.marker.setPosition(e.latLng);
    $('#location').val(PROJC.PICTURES.ADD.marker.position.toString());
  });
};

$(document).ready(function(){PROJC.PICTURES.ADD.init();});
