const LGLS = LG.LiveStream = {};
LGLS.photoMap = null;
LGLS.bounds = null;

const HTML_LOADING = '<img src="/img/loading.gif" />';
const PHOTOSTUB_LIVESTREAM = [
'<div class="photo">',
  '<a href="/photos/view/<%=photo.id%>"><img src="/photos/<%=photo.id%>/3" /></a>',
  '<a class="like" href="#like">230</a>',
'</div>',
'<div class="detail">',
  '<h1><%=photo.user.name%></h1>',
  '<p><span class="time"><%=photo.datetime%> from</span> <span',
  'class="location"><%=photo.location%></span></p>',
  '<p><span class="caption"><%=photo.caption%></span></p>',
  '<div class="bottom">',
    '<p class="comments"><a href="#">7 people</a> have commented</p>',
    '<form class="comments" name="comments" action="/photos/edit/<%=photo.id%>" method="post">',
      '<input name="photo[comment]" type="text" class="default" value="add comment" />',
      '<input type="submit" value="comment" />',
    '</form>',
    '<p>Tags <% for (var i = 0; i < photo.length; i += 1) {%><a href="#"><%=photo[i].tag%></a>, <%}%></p>',
    '<form class="tags" name="tags" action="/photos/edit/<%=photo.id%>" method="post">',
      '<input name="photo[tag]" type="text" class="default" value="add tag" />',
      '<input type="submit" value="tag" />',
    '</form>',
  '</div>',
'</div>'
].join('');

$(function() {
  const GM = google.maps;
  function photo_stub_live_stream(jdom, photo_id) {
    jdom.html(HTML_LOADING);
    $.get('/photos/'+photo_id, function (data) {
      jdom.html(tmpl(PHOTOSTUB_LIVESTREAM, data));

      var coord = new GM.LatLng(data.latitude, data.longitude);
      var LS = LGLS;
      var marker = new GM.Marker({position: coord, map: LS.photoMap});
      LS.bounds.extend(coord);
      LS.photoMap.panToBounds(LS.bounds);
      LS.photoMap.setCenter(LS.bounds.getCenter());
    });
  }
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
});
