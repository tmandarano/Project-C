var LG = defaultTo(LG, {});

LG.map = defaultTo(LG.map, {});
LG.map.defaultLoc = new GM.LatLng(32.7155, -117.1636); // San Diego 1st and Broadway
LG.map.markers = defaultTo(LG.map.markers, {});
LG.map.markers.photo = function (photo) {
  return new GM.Marker({
    title: [photo.user.name, ': ', photo.caption].join(''),
    icon: new GM.MarkerImage('photo/'+photo.id)
  });
};

LG.eye = {
  map: null
};
LG.eye.addPhoto = function (photo_id) {
  var marker = LG.photoMarker(pic);
  marker.setMap(map);
  GM.event.addListener(marker, 'click', function () {
    window.location = '/photos/view/' + photo_id;
  });
  LG.pics.push(marker);
};

LG.eye.initMap = function (div) {
  var latlng = LG.map.defaultLoc; //defaultTo(LG.loc.get(), LG.map.defaultLoc);
  var mapOpts = {
    zoom: 7,
    center: latlng,
    mapTypeId: GM.MapTypeId.TERRAIN,
  };
  LG.eye.map = new GM.Map(div, mapOpts);
};
LG.eye.init = function () {
  LG.eye.initMap($('#map').css({height: 500, width: '100%'})[0]);
};

$(LG.eye.init);
