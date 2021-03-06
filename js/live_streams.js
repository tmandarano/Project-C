var GM = google.maps;
var LGLS = LG.LiveStream = {};
LGLS.photoMap = null;
// Provide a single bounds for JUST the markers. Don't try to extend the
// map's bounds.
LGLS.bounds = null;

var HTML_LOADING = '<img src="/img/loading.gif" />';
var PHOTOSTUB_LIVESTREAM = [
'<div class="photo">',
  '<a href="/api/photos/<%=id%>/iOS/m"><img src="/api/photos/<%=id%>/iOS/m" /></a>',
  '<a class="like" href="#like">230</a>',
'</div>',
'<div class="detail">',
  '<h1><%=user.name%></h1>',
  '<p><span class="time"><%=datetime%> from</span> <span ',
  'class="location"><%=location%></span></p>',
  '<p><span class="caption"><%=caption%></span></p>',
  '<div class="bottom">',
//    '<p class="comments"><a href="#">7 people</a> have commented</p>',
//    '<form class="comments" name="comments" action="/photos/edit/<%=id%>" method="post">',
//      '<input name="photo[comment]" type="text" class="default" value="add comment" />',
//      '<input type="submit" value="comment" />',
//    '</form>',
    '<p>Tags <% for (var i = 0; i < tags.length; i += 1) {%><a href="#"><%=tags[i].tag%></a>, <%}%></p>',
    '<form class="tags" name="tags" action="/api/photos/edit/<%=id%>" method="post">',
      '<input name="photo[tag]" type="text" class="default" value="add tag" />',
      '<input type="submit" value="tag" />',
    '</form>',
  '</div>',
'</div>'
].join('');

LGLS.tmplLivestream = tmpl(PHOTOSTUB_LIVESTREAM);

function loadLivestreamPhoto(jdom, photo_id) {
  jdom.addClass('photo');
  jdom.html(HTML_LOADING);
  $.get('/api/photos/'+photo_id, function (data) {
    var photo = data[0];
    var coord = new GM.LatLng(photo.latitude, photo.longitude);
    new GM.Marker({position: coord, map: LGLS.photoMap});
    LGLS.bounds.extend(coord);
    LGLS.photoMap.fitBounds(LGLS.bounds);

    photo.datetime = photo.date_added;

    if (photo.user_id) {
      $.get('/users/'+photo.user_id, function (data) {
        photo.user = data;
        jdom.html(LGLS.tmplLivestream(photo));
      }, 'json');
    } else {
      photo.user = {name: 'Unknown'};
      jdom.html(LGLS.tmplLivestream(photo));
    }
  }, 'json');
}

$(function() {
  var _ = {};

  // Living event handler for all photos
  $('.photo').live('hover', function(event) {
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
      li.html(HTML_LOADING);
      lis.push(li);
      $('#stream').append(li);
    }
    var offset = _.lastLoaded ? _.lastLoaded : 0 + loadnum;
    $.get('/users/' + _.user.id + '/photos/'+offset +'/' + loadnum, function(photos) {
      for (var i = 0; i < loadnum; i++) {
        loadLivestreamPhoto($('<li></li>').appendTo('ol.live.stream'), photos[i]);
      }
    });
  });

  var mapOpts = {
    zoom: 7,
    center: new GM.LatLng(0, 0),
    mapTypeId: GM.MapTypeId.ROADMAP,
    scrollwheel: true,
    draggable: true,
    disableDefaultUI: false,
    mapTypeControl: true,
    navigationControl: true
  };
  LGLS.photoMap = new GM.Map($("#photo_map")[0], mapOpts);
  LGLS.bounds = new GM.LatLngBounds();

  for (var i in LGLS._streamPhotos) {
    loadLivestreamPhoto($('<li></li>').appendTo('ol.live.stream'), LGLS._streamPhotos[i]);
  }
  LG.G.html.collage.photos($('.suggested.photos'), LGLS._suggestedPhotos);
  LG.G.html.collage.people($('.suggested.people'), LGLS._suggestedPeople);
});
