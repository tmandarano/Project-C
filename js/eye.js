LG.map = defaultTo(LG.map, {});
LG.map.defaultLoc = new GM.LatLng(32.7155, -117.1636); // San Diego 1st and Broadway
LG.map.markers = defaultTo(LG.map.markers, {});
LG.map.markers.photo = function (photo) {
  var marker = null;
  $.ajax({async: false, url: '/users/' + photo.user_id, dataType: 'json',
    success: function (user) {
      marker = new GM.Marker({
        title: [user.username, ': ', photo.caption].join(''),
        icon: new GM.MarkerImage('photo/'+photo.id)
      });
    }
  });
  return marker;
};

LG.eye = (function () {
  var _ = {
    map: null
  };
  _.addPhoto = function (photo) {
    var marker = LG.map.markers.photo(photo);
    marker.setMap(_.map);
    GM.event.addListener(marker, 'click', function () {
      window.location = '/photos/view/' + photo.id;
    });
  };

  _.initTags = function () {
    // Want: 
    // fetch trending tags
    // scroll trending tags across top of screen
    //
    // as the header travels off the screen to the left a new header appears on
    // the right.
    //
    // memory mgmt
    $('#trendingtags');
  };

  _.initMap = function (div) {
    var latlng = LG.map.defaultLoc; //defaultTo(LG.loc.get(), LG.map.defaultLoc);
    var mapOpts = {
      zoom: 7,
      center: latlng,
      mapTypeId: GM.MapTypeId.TERRAIN,
    };
    _.map = new GM.Map(div, mapOpts);

    _.addPhoto({user_id:42,id:99,name:null,latitude:null,longitude:null,location:null,caption:"",tags:[{id:33,tag:"tag1"}]});
  };
  _.resizeMap = function () {
    // Resize map so the livestream is always at the bottom.
    const outerHeight = function (e) { return $(e).outerHeight(); };
    $('#map').height(Math.max(0, $(window).height() - 
      sum($.map(['#header', '#trendingtags', '#livestream', '#softener'],
            outerHeight)) - 8));
    GM.event.trigger(_.map, 'resize');
  };
  _.init = function () {
    _.initMap($('#map').width('100%')[0]);
    $(window).resize(_.resizeMap);
    _.resizeMap();
  };
  return _;
})();

$(LG.eye.init);
