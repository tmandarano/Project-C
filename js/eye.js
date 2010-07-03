LG.map = defaultTo(LG.map, {});
LG.map.defaultLoc = new GM.LatLng(32.7155, -117.1636); // San Diego 1st and Broadway
LG.map.defaultLoc = new GM.LatLng(0, 0); // San Diego 1st and Broadway
LG.map.markers = defaultTo(LG.map.markers, {});
LG.map.markers.photo = function (photo) {
  var marker = null;
  $.ajax({async: false, url: ['/api/photos/', photo.id, '/user'].join(''),
    dataType: 'json',
    success: function (user) {
      try {
      marker = new GM.Marker({
        position: new GM.LatLng(photo.latitude, photo.longitude),
        title: [user.username, ': ', photo.caption].join(''),
        icon: new GM.MarkerImage(['api/photos/', photo.id, '/1'].join(''), null, null, new GM.Point(20, 42)),
        shadow: new GM.MarkerImage('/img/mapmkrbdr.png')
      });
      } catch (e) {
        console.log(e);
      }
    }
  });
  return marker;
};

LG.eye = (function () {
  var _ = {
    map: null,
    lastPhotos: [],
    search: null
  };
  _.setSearch = function (q) {
    _.search = q;
  };
  _.doSearch = function (q) {
    console.log('searching for', q);
    // TODO waiting for API
    // TODO for real
    var jitter = function () { return Math.random() - 0.5; };
    var photos = [
    {id:112,user_id:122,latitude:_.map.getCenter().lat() + jitter(),longitude:_.map.getCenter().lng() + jitter(),caption:"a",tags:[{id:33,tag:"tag1"}]},
    {id:112,user_id:122,latitude:_.map.getCenter().lat() + jitter(),longitude:_.map.getCenter().lng() + jitter(),caption:"b",tags:[{id:33,tag:"tag1"}]},
    {id:112,user_id:122,latitude:_.map.getCenter().lat() + jitter(),longitude:_.map.getCenter().lng() + jitter(),caption:"c",tags:[{id:33,tag:"tag1"}]},
    {id:112,user_id:122,latitude:_.map.getCenter().lat() + jitter(),longitude:_.map.getCenter().lng() + jitter(),caption:"d",tags:[{id:33,tag:"tag1"}]},
    {id:112,user_id:122,latitude:_.map.getCenter().lat() + jitter(),longitude: _.map.getCenter().lng() + jitter(),caption:"e",tags:[{id:33,tag:"tag1"}]}
    ];

    for (var i in _.lastPhotos) {
      if (_.lastPhotos[i]) {
        _.lastPhotos[i].setMap(null);
      }
    }
    
    try {
      for (var i in photos) {
        _.lastPhotos.push(_.addPhoto(photos[i]));
      }
    } catch (e) {
        console.log(e);
    }
  };
  _.addPhoto = function (photo) {
    var marker = LG.map.markers.photo(photo);
    if (marker) {
      marker.setMap(_.map);
      GM.event.addListener(marker, 'click', function () {
        LG.G.showPhoto(photo.id);
        return false;
      });
    }
    return marker;
  };
  _.initTags = function () {
    var alltags = $('#trendingtags ol');
    var headertag = $('<li><h1>Trending tags</h1></li>');
    LG.G.fade(alltags.parent());

    function showNowTrendingTags() {
      $.get('/api/tags/trending/20', function (tags) {
        var tags = $.map(tags, function (t) { return t.tag; });
        for (var i in tags) {
          alltags.append($('<li></li>').append(
            $(['<a href="#">', tags[i], '</a>'].join(''))
              .click(function () {
                _.doSearch('tag:'+tags[i]); TODO
                return false;
              })));
        }
      }, 'json');
    }

    alltags.append(headertag).hide();

    showNowTrendingTags();
    //setTimeout(showNowTrendingTags, 60000);
    alltags.fadeIn('fast');
  };
  _.initMap = function (div, initAfterMap) {
    LG.loc.get(function (latlng) {
      latlng = defaultTo(latlng, LG.map.defaultLoc);
      console.log(latlng);
      var mapOpts = {
        zoom: 13,
        center: latlng,
        mapTypeId: GM.MapTypeId.ROADMAP,
      };
      _.map = new GM.Map(div, mapOpts);
      initAfterMap();
    });
  };
  _.resize = function () {
    // Resize map so the livestream is always at the bottom.
    var outerHeight = function (e) { return $(e).outerHeight(); };
    var contentHeight = Math.max(0, $(window).height() - 
      sum($.map(['#header', '#trendingtags', '#footer'], outerHeight)));
    var sidebarWidth = 300;
    $('#map').height(contentHeight).width($('#content').width() - sidebarWidth);
    $('#livestream').height(contentHeight).width(sidebarWidth);
    GM.event.trigger(_.map, 'resize');
  };
  _.addPhotoLivestream = function (photo) {
    var id = photo.id;
    var tags = '';
    for (var i in photo.tags) {
      tags += '<li>' + photo.tags[i].tag + '</li>';
    }
    $.get('/api/users/' + photo.user_id, function (user) {
      var photoInfo = $(['<li><img class="clickable" src="/api/photos/', id, '/3" />',
         '<div>',
         '<p class="time">', LG.dateToVernacular(photo.date_added), '</p>',
         '<p class="user">', user ? user.username : 'Unknown user', '</p>',
         '<p class="location">', photo.location, '</p>',
         '<ul class="tags">', tags, '</ul>',
         '</div></li>'
        ].join(''));
      photoInfo.find('img').click(function () { LG.G.showPhoto(id); });
      photoInfo.find('li').click(function () { alert('will search for ' + $(this).html()); });

      _.alllive.append(photoInfo);
    }, 'json');
  };
  _.initLivestream = function () {
    _.alllive = $('#livestream ol');

    $.get('/api/photos/recent/20', function (photos) {
      for (var i in photos) {
        _.addPhotoLivestream(photos[i]);
      }
    }, 'json');

    _.alllive.hide().fadeIn('fast');
  };
  _.initSearch = function () {
    $('#search form').submit(function () {
      _.doSearch($('input', this).val());
      return false;
    });
  };
  _.update = function () {
    console.log('updating');
    if (_.pause) {
      console.log('paused');
    } else {
      _.doSearch(_.search);
    }
    setTimeout(arguments.callee, 8000);
  };
  _.init = function () {
    function initAfterMap() {
      _.initLivestream();
      _.initSearch();
      _.update();
      $(window).resize(_.resize);
      _.resize();
    }
    _.initMap($('#map').width('100%')[0], initAfterMap);
    _.initTags();

    //var button = $('<button>Click me</button>');
    //button.click(function () {
    //  $('<div hello="1">Hello</div>').dialog().parent().parent().addClass('lg-dialog');
    //  return false;
    //});
    //$('#content').prepend(button);
  };
  return _;
})();

$(LG.eye.init);
