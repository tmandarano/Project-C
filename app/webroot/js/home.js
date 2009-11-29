var LG = LG ? LG : {};
LG.HOME = {};
LG.HOME.TIME_BETWEEN = 5000;
LG.HOME.MAX_PICS_IN_STREAM = 8;
LG.HOME.map = null;
/* Overlay displays a caption bubble over the map with the user, caption,
 * location, and time. Picture is displayed over the center of the map which
 * centers on the location. Picture is also faded into the right-most empty
 * spot of the stream. If there's too many, bump out the left-most picture.
 */
LG.HOME.QueueOverlay = function() {
  this.captionPane = $('<div id="map_caption"></div>');
  this.picturePane = $('<div id="map_picture"></div>');
  this.stream = $('#updating_map_stream');
  this.picturePane.children('img').css({'width': '50px', 'height': '50px'});
};
LG.HOME.QueueOverlay.prototype = new google.maps.OverlayView();
LG.HOME.QueueOverlay.prototype.onAdd = function() {
  $(this.getMap().getDiv()).append(this.captionPane);
  $(this.getMap().getDiv()).append(this.picturePane);
};
LG.HOME.QueueOverlay.prototype.draw = function() {
};
LG.HOME.QueueOverlay.prototype.onRemove = function() {
  $(this.getMap().getDiv()).empty();
};
LG.HOME.QueueOverlay.prototype.addPic = function(pic) {
  var self = this;
  function appendStream() {
    self.getMap().panTo(new google.maps.LatLng(pic.lat, pic.lng));
    var caption = '<img src="/img/mini_pic.jpg" /><a href="#">'+
      pic.User.name+'</a><p>'+pic.caption+
      '</p><div class="location">San Diego, CA</div><div class="time">6 seconds ago</div>';
    self.captionPane.hide().html(caption).fadeIn(600);
    self.picturePane.hide().html('<img src="/img/db/'+pic.id+'.jpg" />').fadeIn(600);
    self.stream.children().fadeTo(600, 0.5);
    self.stream.append($('<img src="/img/db/'+pic.id+'.jpg" />').fadeIn(600));
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
