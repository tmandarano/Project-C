LG.LiveStream = {};
LG.LiveStream.photoMap = null;
LG.LiveStream.bounds = null;
function photo_stub_live_stream(jdom, photo_id) {
  jdom.html('<img src="/img/loading.gif" />');
  $.get('/shard/photo_stub_live_stream/'+photo_id, function(data) {
    jdom.html(data);
  });
  $.get('/photos/show/'+photo_id, function(data) {
      var lat = data.lat; // TODO
      var lng = data.lng; // TODO
      lat = 47.5619+(Math.random()-0.5);
      lng = -122.2164+(Math.random()-0.5);
      var coord = new google.maps.LatLng(lat, lng);
      LG.LiveStream.bounds.extend(coord);
      LG.LiveStream.photoMap.panToBounds(LG.LiveStream.bounds);
      LG.LiveStream.photoMap.setCenter(LG.LiveStream.bounds.getCenter());
      marker = new google.maps.Marker({position: coord, map: LG.LiveStream.photoMap});
    }, 'json');
}
$(function() {
  $('.photo.stub').each(function(index, elem) {
      var stubarr = $(elem).attr('stub').split('|');
      var call = stubarr[0];
      var photo_id = stubarr[1];
      if (call == "live_stream") {
        photo_stub_live_stream($(elem), photo_id);
      } else {
        // Error. Unknown stub.
      }
    });
  $('.photo')
    .live('hover', function(event) {
      if (event.type == 'mouseover') {
        $('a.like', this).show();
      } else {
        $('a.like', this).hide();
      }
    });

  $('.loadmore a').click(function() {
    var loadnum = 4;
    var lis = [];
    for (var i = 0; i < loadnum; i++) {
      var li = $('<li></li>');
      li.html('<img src="/img/loading.gif" />');
      lis.push(li);
      $('#stream').append(li);
    }
    $.get('/users/photo', function(data) {
      /* TODO make the right API call to get the next few photo ids */
      var ids = [451, 452, 453, 451];
      for (var i = 0; i < loadnum; i++) {
        photo_stub_live_stream(lis[i], ids[i]);
      }
    });
  });

  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(0, 0),
    mapTypeId: google.maps.MapTypeId.HYBRID,
    scrollwheel: true,
    draggable: true,
    disableDefaultUI: false,
    mapTypeControl: true,
    navigationControl: true
  };
  LG.LiveStream.photoMap = new google.maps.Map($("#photo_map")[0], mapOpts);
  LG.LiveStream.bounds = new google.maps.LatLngBounds();
});
