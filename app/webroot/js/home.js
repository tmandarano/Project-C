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
  this.captionPane = $('<div id="map_caption"><img src="/img/mini_pic.jpg" /><a href="#">username</a><p>Just saw a wicked accident on the freeway. Hope everyone is okay.</p><div class="location">San Diego, CA</div><div class="time">6 seconds ago</div></div>');
};
overlay.prototype = new google.maps.OverlayView();
overlay.prototype.onAdd = function() {
  $(this.getMap().getDiv()).append(this.captionPane);
  var up = $('#updating_map_stream');
  up.append($('<img src="/img/db/8.jpg" />'));
  up.append($('<img src="/img/db/7.jpg" />'));
  up.append($('<img src="/img/db/5.jpg" />'));
  up.append($('<img src="/img/db/5.jpg" />'));
  up.append($('<img src="/img/db/3.jpg" />'));
  up.append($('<img src="/img/db/3.jpg" />'));
  up.append($('<img src="/img/db/2.jpg" />'));
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
