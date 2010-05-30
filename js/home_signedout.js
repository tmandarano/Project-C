LG.HOME = (function() {
var H = {};
/* Overlay displays a caption bubble over the map with the user, caption,
 * location, and time. Photo is displayed over the center of the map which
 * centers on the location. */
H.PlotOverlay = (function() {
var HP = function() {
  this.captionPane = $('<div id="map_caption"></div>').hide();
  this.photoPane = $('<div id="map_photo"></div>').hide();
};
var HPP = HP.prototype = new google.maps.OverlayView();
HPP.onAdd = function() { $(this.getMap().getDiv()).append(this.captionPane).append(this.photoPane); };
HPP.draw = function() {};
HPP.onRemove = function() { $(this.getMap().getDiv()).empty(); };
HPP.show = function(json) {
  this.captionPane.hide();
  this.photoPane.hide()
  var photo = json;
  photo.location = "location";
  photo.lat = 32;
  photo.lng = -117;
  photo.user = {'id': 1, 'name': 'name'}; // TODO
  var usr = photo.user;
  this.getMap().panTo(new google.maps.LatLng(photo.lat, photo.lng));
  var caption = '<div class="users">'+
    '<a href="/profile/'+usr.id+'"><img src="/users/photo/'+usr.id+'" /></a>'+
    '<a href="/profile/'+usr.id+'" class="username">'+usr.name+'</a> '+
    '<span class="time">'+photo.datetime+'</span> '+
    '<span class="location">'+(photo.location || 'Location unknown')+'</span>'+
    '<p class="caption">'+photo.caption+'</p></div>';
  this.captionPane.html(caption).fadeIn(600);
  this.photoPane.html($('<a href="#"><img src="/photo/'+photo.id+'/2" /></a>')
    .click(function() {viewpic(photo.id);})).fadeIn(600);
};
return HP;
})();
H.init = function() {
  var jdom = $('#map');
  var HS = LG.G.headerStream;
  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    draggable: false,
    disableDoubleClickZoom: true,
    disableDefaultUI: true,
    mapTypeControl: false
  };
  H.map = new google.maps.Map(jdom[0], mapOpts);
  H.plot = new H.PlotOverlay();
  H.plot.setMap(H.map);
  H.arrow = $('<img src="/img/arrow_up.png" />').hide().appendTo($('body'));
  HS.jdom.bind('change', function() {
    function findPhotoOverMap() {
      var jdom = $('#map');
      var x = jdom.offset().left+jdom.width()/2;
      var photoPadWidth = parseInt(HS.jdom.css('padding-left').slice(0, -2), 10);
      var photoScrollWidth = HS.jdom.width()-2*photoPadWidth;
      return Math.floor(1.0 * (x-photoPadWidth) / photoScrollWidth * HS.getMaxPhotos());
    }
    var photo = findPhotoOverMap();
    var children = HS.jdom.children();
    var jdom = $(children[photo]);
    H.arrow.css({'position': 'absolute', 'top': $('#map').offset().top-17,
      'left': jdom.offset().left-jdom.width()+(jdom.width()-32)/2}).show();
    H.plot.show(jdom.data('json'));
    $(children[photo-1]).css('border', 'none');
    jdom.css('border', '1px solid #fff');
  });
};
return H;
})();
$(document).ready(LG.HOME.init);
