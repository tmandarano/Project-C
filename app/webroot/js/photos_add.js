var LG = LG ? LG : {};
LG.PICTURES = LG.PICTURES ? LG.PICTURES : {};
LG.PICTURES.ADD = {};
LG.PICTURES.ADD.init = function() {
  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    disableDefaultUI: true,
    mapTypeControl: false,
    navigationControl: true,
    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL}
  };
  var map = LG.PICTURES.ADD.map = new google.maps.Map($("#map")[0], mapOpts);
  LG.PICTURES.ADD.marker = new google.maps.Marker({
    position: map.getCenter(),
    draggable: true,
    map: map
  });
  function setLocation(latlng) {
    $('#lng').val(latlng.lng());
    $('#lat').val(latlng.lat());
  }
  google.maps.event.addListener(LG.PICTURES.ADD.marker, 'click', function(e) {
    setLocation(LG.PICTURES.ADD.marker.position);
  });
  google.maps.event.addListener(map, 'click', function(e) {
    LG.PICTURES.ADD.marker.setPosition(e.latLng);
    setLocation(LG.PICTURES.ADD.marker.position);
  });
  google.maps.event.addListener(map, 'bounds_changed', function() {
    LG.PICTURES.ADD.marker.setPosition(this.getCenter());
    setLocation(LG.PICTURES.ADD.marker.position);
  });
};
$(document).ready(LG.PICTURES.ADD.init);
