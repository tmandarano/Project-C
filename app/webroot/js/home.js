var PROJC = PROJC ? PROJC : {};
PROJC.pics = [];
PROJC.picMarker = function(pic) {
  var markerOpts = {
    title: pic.User.name+': '+pic.caption,
    position: new google.maps.LatLng(pic.lat, pic.lng),
    icon: new google.maps.MarkerImage('pictures/'+pic.id/*,
      new google.maps.Size(pic.width, pic.height)*/
    )
  }
  return new google.maps.Marker(markerOpts);
};
PROJC.mapAddPic = function(map, pic) {
  var marker = PROJC.picMarker(pic);
  marker.setMap(map);
  PROJC.pics.push(marker);
};


PROJC.HOME = {};
PROJC.HOME.map = null;
var overlay = PROJC.HOME.overlay = function() {
  this.captionPane = $('<div id="map_caption">MAP PANE</div>');
};
overlay.prototype = new google.maps.OverlayView();
overlay.prototype.onAdd = function() {
  $(this.getPanes().floatPane).append(this.captionPane);
};
overlay.prototype.draw = function() {
  this.captionPane;
};
overlay.prototype.onRemove = function() {
  //remove obj from dom
  $(this.getPanes().floatPane).empty();
};
PROJC.HOME.switchPic = function(pic) {
  if (PROJC.pics.length > 0) {
    PROJC.pics.pop().map = null;
  }
  PROJC.mapAddPic(PROJC.HOME.map, pic);
};
function cyclesamplepics() {
  jQuery.each(PROJC.recentPictures, function() {
    PROJC.pics.push(PROJC.picMarker(this));
  });
  function swappic() {
    var oldpic = PROJC.pics[0];
    oldpic.setMap(null);
    PROJC.pics.push(PROJC.pics.shift());
    PROJC.pics[0].setMap(PROJC.HOME.map);
    PROJC.HOME.map.panTo(PROJC.pics[0].getPosition());
    setTimeout(swappic, 3000);
  }
  swappic();
}
PROJC.HOME.init = function() {
  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    disableDefaultUI: true,
    mapTypeControl: false
  };
  PROJC.HOME.map = new google.maps.Map($("#map")[0], mapOpts);
  cyclesamplepics();
  var OVERLAY = new PROJC.HOME.overlay();
  OVERLAY.setMap(PROJC.HOME.map);
};

$(document).ready(function(){PROJC.HOME.init();});
