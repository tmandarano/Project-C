var LG = LG ? LG : {};
LG.HOME = {};
LG.HOME.TIME_BETWEEN = 5000;
LG.HOME.MAX_PICS_IN_STREAM = 8;
LG.HOME.map = null;
/* Overlay displays a caption bubble over the map with the user, caption,
 * location, and time. Photo is displayed over the center of the map which
 * centers on the location. Photo is also faded into the right-most empty
 * spot of the stream. If there's too many, bump out the left-most photo.
 */
LG.HOME.QueueOverlay = function() {
  this.captionPane = $('<div id="map_caption"></div>');
  this.photoPane = $('<div id="map_photo"></div>');
  this.stream = $('#updating_map_stream');
  this.photoPane.children('img').css({'width': '50px', 'height': '50px'});
};
LG.HOME.QueueOverlay.prototype = new google.maps.OverlayView();
LG.HOME.QueueOverlay.prototype.onAdd = function() {
  $(this.getMap().getDiv()).append(this.captionPane);
  $(this.getMap().getDiv()).append(this.photoPane);
};
LG.HOME.QueueOverlay.prototype.draw = function() {
};
LG.HOME.QueueOverlay.prototype.onRemove = function() {
  $(this.getMap().getDiv()).empty();
};
LG.HOME.QueueOverlay.prototype.addPic = function(photo) {
  var self = this;
  function appendStream() {
    var pic = photo.Photo;
    var usr = photo.User;
    self.getMap().panTo(new google.maps.LatLng(pic.lat, pic.lng));
    var caption = '<img src="/users/photo/'+usr.id+'" /><a href="/users/profile/'+usr.id+'">'+
      usr.name+'</a><p>'+pic.caption+
      '</p><div class="location">San Diego, CA</div><div class="time">'+pic.time+'</div>';
    self.captionPane.hide().html(caption).fadeIn(600);
    self.photoPane.hide().html('<img src="/photos/'+pic.id+'/1" />').fadeIn(600);
    self.stream.children().fadeTo(600, 0.5);
    self.stream.append($('<img src="/photos/'+pic.id+'/1" />').fadeIn(600));
  }
  if (this.stream.children().length >= LG.HOME.MAX_PICS_IN_STREAM) {
    $(this.stream.children()[0]).hide(300, function() {
      $(this).remove();
      appendStream();
    });
  } else {
    appendStream();
  }
};
function cycleSamplePics() {
  function swappic() {
    LG.recentPhotos.push(LG.recentPhotos.shift());
    LG.HOME.queue.addPic(LG.recentPhotos[0]);
    setTimeout(swappic, LG.HOME.TIME_BETWEEN);
  }
  swappic();
}
LG.HOME.init = function() {
  $('body').css('background', "url('/img/bg.png') no-repeat fixed top center");

  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    draggable: false,
    disableDoubleClickZoom: true,
    disableDefaultUI: true,
    mapTypeControl: false
  };
  LG.HOME.map = new google.maps.Map($("#map")[0], mapOpts);
  LG.HOME.queue = new LG.HOME.QueueOverlay();
  LG.HOME.queue.setMap(LG.HOME.map);

  cycleSamplePics();
};
$(document).ready(function(){LG.HOME.init();});
