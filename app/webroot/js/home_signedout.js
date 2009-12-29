LG.HOME = {};
/* Overlay displays a caption bubble over the map with the user, caption,
 * location, and time. Photo is displayed over the center of the map which
 * centers on the location. */
LG.HOME.PlotOverlay = function() {
  this.captionPane = $('<div id="map_caption"></div>').hide();
  this.photoPane = $('<div id="map_photo"></div>').hide();
};
LG.HOME.PlotOverlay.prototype = new google.maps.OverlayView();
LG.HOME.PlotOverlay.prototype.onAdd = function() { $(this.getMap().getDiv()).append(this.captionPane).append(this.photoPane); };
LG.HOME.PlotOverlay.prototype.draw = function() {};
LG.HOME.PlotOverlay.prototype.onRemove = function() { $(this.getMap().getDiv()).empty(); };
LG.HOME.PlotOverlay.prototype.show = function(json) {
  this.captionPane.hide();
  this.photoPane.hide()
  var photo = json.Photo;
  var usr = json.User;
  this.getMap().panTo(new google.maps.LatLng(photo.location[0], photo.location[1]));
  var caption = '<div class="users">'+
    '<a href="/users/profile/'+usr.id+'"><img src="/users/photo/'+usr.id+'" /></a>'+
    '<a href="/users/profile/'+usr.id+'" class="username">'+usr.name+'</a> '+
    '<span class="time">'+photo.datetime+'</span> '+
    '<span class="location">'+(photo.location[2] || 'Location unknown')+'</span>'+
    '<p class="caption">'+photo.caption+'</p></div>';
  this.captionPane.html(caption).fadeIn(600);
  this.photoPane.html($('<a href="#"><img src="/photos/'+photo.id+'/3" /></a>')
    .click(function() {viewpic(photo.id);})).fadeIn(600);
};
LG.HOME.init = function() {
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
  LG.HOME.map = new google.maps.Map(jdom[0], mapOpts);
  LG.HOME.plot = new LG.HOME.PlotOverlay();
  LG.HOME.plot.setMap(LG.HOME.map);
  LG.HOME.arrow = $('<img src="/img/arrow_up.png" />').hide().appendTo($('body'));
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
    LG.HOME.arrow.css({'position': 'absolute', 'top': $('#map').offset().top-17,
      'left': jdom.offset().left-jdom.width()+(jdom.width()-32)/2}).show();
    LG.HOME.plot.show(jdom.data('json'));
    $(children[photo-1]).css('border', 'none');
    jdom.css('border', '1px solid #fff');
  });
};
$(document).ready(LG.HOME.init);
