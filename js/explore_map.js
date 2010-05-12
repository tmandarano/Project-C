var LG = LG ? LG : {};
LG.pics = [];
LG.picMarker = function(pic) {
  var markerOpts = {
    title: pic.User.name+': '+pic.caption,
    position: new google.maps.LatLng(pic.lat, pic.lng),
    icon: new google.maps.MarkerImage('pictures/'+pic.id/*,
      new google.maps.Size(pic.width, pic.height)*/
    )
  }
  return new google.maps.Marker(markerOpts);
};
LG.mapAddPic = function(map, pic) {
  var marker = LG.picMarker(pic);
  marker.setMap(map);
  google.maps.event.addListener(marker, 'click', function() {
    viewpic(pic.id);
  });
  LG.pics.push(marker);
};

var map;

$(function () {
  var latlng = new google.maps.LatLng(32.77977,-117.137947);
  var mapOpts = {
    zoom: 7,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    disableDefaultUI: true,
    mapTypeControl: false
  };
  
  map = new google.maps.Map(document.getElementById("map_explore"), mapOpts);

  LG.mapAddPic(map, {
    id:'2',
    caption:'',
    lat:'34',
    lng:'-117',
    User:{name:'testuser!123'}
  });
  LG.mapAddPic(map, {
    id:'3',
    caption:'',
    lat:'39',
    lng:'-114',
    User:{name:'testuser!123'}
  });

  // Shelf needs to be transparent
  $('#shelf').fadeTo(0, 0.9);
});
