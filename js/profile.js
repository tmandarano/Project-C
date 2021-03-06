var HTML_BOTTOM = [
  '<div class="bottom">',
    //'<p class="comments"><a href="#">7 people</a> have commented</p>',
    //'<form class="comments" name="comments"',
    //'action="/photos/edit/{$mostRecent.id}" method="post">',
    //  '<input name="photo[comment]" type="text" class="defaultable" value="add comment" />',
    //  '<input type="submit" value="comment" />',
    //'</form>',
    '<p>Tags <% if (x.tags) { ',
      'for (var i = 0; i < Math.min(LG.profile.MAX_TAGS, x.tags.length); i += 1) {',
      '%><a href="#"><%=x.tags[i].tag%></a> <%}}%></p>',
    '<form class="tags" name="tags" action="/api/photos/<%=x.id%>/tags" method="POST">',
      '<input name="tag" type="text" class="defaultable" value="add tag" />',
      '<input type="submit" value="tag" />',
    '</form>',
  '</div>'
].join('');
var HTML_PHOTO = [
  '<div class="photo" photo_id="<%=x.id%>">',
    '<a href="#"><img src="/api/photos/<%=x.id%>/iOS/f" /></a>',
    //'<a class="like" href="#like">Like</a>',
  '</div>'
].join('');
var HTML_MOST_RECENT = [
  '<div class="most_recent">',
    '<p><span class="caption"><%=x.caption%></span> ',
       '<span class="time"><%=LG.dateToVernacular(x.date_added)%> from</span> ',
       '<span class="location"><%=x.location%></span></p>',
    '<ol class="profile stream">',
      '<li>',
        HTML_PHOTO,
        '<div class="detail">',
          '<div class="map" lat="<%=x.latitude%>" lng="<%=x.longitude%>"></div>',
          HTML_BOTTOM,
        '</div>',
      '</li>',
    '</ol>',
  '</div>'
].join('');

$(function () {
  var _ = LG.profile;
  _.MAX_TAGS = 20;

  $('.left .username a').html(_.user.username);
  $('.left .userphoto').attr('src', '/api/users/photo/' + _.user.id);
  
  for (var i in _.similarPeople) {
    var person = _.similarPeople[i];
    $('.collage.similar.people').append($([
      '<li><a href="/profile/', person.id, '"><img src="/api/users/',
      person.id ,'photo"/></a></li>'].join('')));
  }

  for (var i in _.tags) {
    var tag = _.tags[i];
    $('.tagcloud').append($(['<li>', tag.tag, '</li>'].join('')));
  }

  if (_.recentPhotos.length > 0) {
    $('.right').prepend($(tmpl(HTML_MOST_RECENT, {x: _.recentPhotos[0]})));
    var mapjdom = $('.most_recent .map');
    var lat = defaultTo(mapjdom.attr('lat'), 0);
    var lng = defaultTo(mapjdom.attr('lng'), 0);
    var mapOpts = {
      zoom: 12,
      center: new GM.LatLng(lat, lng),
      mapTypeId: GM.MapTypeId.HYBRID,
      scrollwheel: false,
      draggable: false,
      disableDefaultUI: true,
      mapTypeControl: false,
      navigationControl: false
    };
    var map = new GM.Map(mapjdom[0], mapOpts);
    var marker = new GM.Marker({position: map.getCenter(), map: map});
  }

  var HTML_PROFILE_STUB = [
    '<li class="photo">', 
      HTML_PHOTO,
      '<div class="detail">',
        '<p><span class="time"><%= LG.dateToVernacular(x.date_added)%> from</span> <span',
        'class="location"><%=x.location%></span></p>',
        '<p><span class="caption"><%=x.caption%></span></p>',
        HTML_BOTTOM,
      '</div>',
    '</li>'].join('');
  for (var i = 1; i < _.recentPhotos.length; i += 1) {
    $('.right .profile.stream').append(
      tmpl(HTML_PROFILE_STUB, {x: _.recentPhotos[i]}));
  }

  $('div.photo').live('click', function () {
    LG.G.showPhoto($(this).attr('photo_id'));
  });

  var signed_in_as_user = false; // TODO
  if (signed_in_as_user) {
    $('.profile.stream li').live('mouseenter mouseleave', function (event) {
      if (event.type == 'mouseover') {
        $(this).append($('<div>Hello</div>')
          .css({position: 'absolute', 'top': 140, 'left': 280, 'background': '#aaa',
                padding: '0.5em'})
          .attr('transient', true));
      } else {
        $('div[transient=true]', this).remove();
      }
    });
  }

  $('.loadmore a').click(function() {
    var loadnum = 4;
    var lis = [];
    for (var i = 0; i < loadnum; i++) {
      var li = $('<li><img src="/img/loading.gif" /></li>');
      lis.push(li);
      $('#stream').append(li);
    }
    var offset = _.lastLoaded ? _.lastLoaded : 0 + loadnum;
    $.get('/users/' + _.user.id + '/photos/'+offset +'/' + loadnum, function(photos) {
      for (var i = 0; i < loadnum; i++) {
        lis[i].html(tmpl(HTML_PROFILE_STUB, {x: photos[i]}));
      }
    });
  });

});
