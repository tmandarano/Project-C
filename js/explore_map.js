LG.photoMarker = function(photo) {
  return new GM.Marker({
    title: photo.user.name+': '+photo.caption,
    icon: new GM.MarkerImage('photo/'+photo.id/*,
      new GM.Size(pic.width, pic.height)*/
    )
  });
};

LG.ExploreMap = {
  map: null,
  photos: []
};
LG.ExploreMap.addPhoto = function (photo_id) {
  var marker = LG.photoMarker(pic);
  marker.setMap(map);
  GM.event.addListener(marker, 'click', function () {
    window.location = '/photos/view/' + photo_id;
  });
  LG.pics.push(marker);
};

$(function () {
  var latlng = new GM.LatLng(32.77977,-117.137947);
  var mapOpts = {
    zoom: 7,
    center: latlng,
    mapTypeId: GM.MapTypeId.TERRAIN,
    mapTypeControl: false
  };
  LG.ExploreMap.map = new GM.Map(document.getElementById("map_explore"), mapOpts);

  $('#time').buttonset();
});
