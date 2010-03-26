function photo_stub_profile_stream(jdom, photo_id) {
  jdom.html('<img src="/img/loading.gif" />');
  $.get('/shard/photo_stub_profile_stream/'+photo_id, function(data) {
    jdom.html(data);
  });
}
$(function() {
  $('.tagcloud').tagcloud({
    'type': 'list',
    'height': 'auto',
    'colormin': '2594c2',
    'colormax': '1584b2'});
  $('.photo.stub').each(function(index, elem) {
      var stubarr = $(elem).attr('stub').split('|');
      var call = stubarr[0];
      var photo_id = stubarr[1];
      if (call == "profile_stream") {
        photo_stub_profile_stream($(elem), photo_id);
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
        var photo_id = ids[i];
        $.get('/shard/photo_stub_profile_stream/'+photo_id, (function(li) {
          return function(data) { li.html(data); }})(lis[i]));
      }
    });
  });

  var mapjdom = $('.most_recent .map');
  var lat = mapjdom.attr('lat');
  var lng = mapjdom.attr('lng');
  var mapOpts = {
    zoom: 12,
    center: new google.maps.LatLng(lat, lng),
    mapTypeId: google.maps.MapTypeId.HYBRID,
    scrollwheel: false,
    draggable: false,
    disableDefaultUI: true,
    mapTypeControl: false,
    navigationControl: false
  };
  var map = new google.maps.Map(mapjdom[0], mapOpts);
  var marker = new google.maps.Marker({position: map.getCenter(), map: map});
});
