LG.map = defaultTo(LG.map, {});
LG.map.defaultLoc = new GM.LatLng(32.7155, -117.1636); // San Diego 1st and Broadway
LG.map.defaultLoc = new GM.LatLng(0, 0); // XXX Nowhere in particular so it's obvious
LG.map.markers = defaultTo(LG.map.markers, {});
LG.map.markers.photo = function (photo) {
  var marker = null;
  $.ajax({async: false, url: ['/api/photos/', photo.id, '/user'].join(''),
    dataType: 'json',
    success: function (user) {
      try {
      marker = new GM.Marker({
        position: new GM.LatLng(photo.latitude, photo.longitude),
        title: [(user ? user.username : 'Unknown'), ': ',
                photo.caption].join(''),
        icon: new GM.MarkerImage(['api/photos/', photo.id, '/1'].join(''),
          null, null, new GM.Point(20, 42)),
        shadow: new GM.MarkerImage('/img/mapmkrbdr.png')
      });
      } catch (e) {
        console.log(e);
      }
    }
  });
  return marker;
};

// Update the map and livestream with photos every n seconds with either the
// most recent photos if there is no search, or the photos that match the 
// search query.
LG.eye = (function () {
  var _ = {
    map: null,
    lastPhotos: [],
    updateTimer: null,
    idleDraw: null,
    search: null,
    refreshDelay: 8000
  };
  _.PhotosOverlay = (function () {
    var lastPhotos = [];
    function P() {}
    var Pp = P.prototype = new GM.OverlayView();
    Pp.onAdd = function () {
      var draw = (function (self) { return function () { self.draw(); }; })(this);
      GM.event.addListener(this.get('map'), 'dragend', draw);
    };
    function getDefaultSearcher(self) {
      return function () { self.doSearch(); };
    }
    Pp.draw = function () {
      if (_.idleDraw) {
        GM.event.removeListener(_.idleDraw);
      }
      _.idleDraw = GM.event.addListenerOnce(this.get('map'), 'idle',
        getDefaultSearcher(this));
    };
    Pp.onRemove = function () {
      while (lastPhotos.length > 0) {
        var photo = lastPhotos.pop();
        photo.setMap(null);
        delete photo;
      }
    };
    Pp.addPhoto = function (photo) {
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
    Pp.setSearch = function (q) {
      _.search = q;
      setTimeout(getDefaultSearcher(this), 0);
    };
    Pp.doSearch = function () {
      if (!this.get('map') || !this.get('projection')) {
        return;
      }
      if (_.updateTimer) {
        clearTimeout(_.updateTimer);
      }
      this.onRemove();
      // TODO get most recent photo in viewport matching q
      console.log('searching for', _.search);

      var size = 40;
      size *= 3;

      var div = $(this.get('map').getDiv());
      var width = div.outerWidth(),
          height = div.outerHeight();

      var coord = (function (overlayview) {
        var ptcache = {};
        function key(x, y) {
          return x + ',' + y;
        }
        function _(x, y) {
          var k = key(x, y);
          var val = ptcache[k];
          if (val) {
            return val;
          }
          var latlng = ptcache[k] = overlayview.getProjection()
            .fromContainerPixelToLatLng(new GM.Point(x, y));
          return latlng;
        }
        return _;
      })(this);

      var cells = [];
      for (var x = 0; x < width; x += size) {
        for (var y = 0; y < height; y += size) {
          // nw, ne, se, sw
          var rect = [coord(x, y), coord(x + size, y),
                      coord(x + size, y + size), coord(x, y + size)];
          cells.push(rect);
        }
      }
      
      // TODO don't use fake data
      var tags = [{id:33,tag:"tag1"}];
      var photos = [];
      for (var i in cells) {
        var cell = cells[i];
        var bounds = new GM.LatLngBounds(cell[3], cell[1]);
        var center = bounds.getCenter();
        photos.push({
          id: 111 + i,
          user_id: 122 + i,
          latitude: center.lat(),
          longitude: center.lng(),
          caption: "a" + i,
          tags: tags
        });
      }
      
      // Add found photos
      try {
        for (var i in photos) {
          lastPhotos.push(Pp.addPhoto(photos[i]));
        }
      } catch (e) {
        console.log(e);
      }

      _.updateTimer = setTimeout(getDefaultSearcher(this), _.refreshDelay);
    };
    return new P();
  })();
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
                _.PhotosOverlay.setSearch('tag:'+tags[i]); TODO
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
      console.log('Map initialized to', latlng);
      var mapOpts = {
        zoom: 13,
        center: latlng,
        mapTypeId: GM.MapTypeId.ROADMAP,
      };
      _.map = new GM.Map(div, mapOpts);
      _.PhotosOverlay.setMap(_.map);
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
    setTimeout(function () { _.PhotosOverlay.doSearch(); }, 0);
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
    var geo = new GM.Geocoder();
    $('#search form').submit(function () {
      _.PhotosOverlay.setSearch($('input[name=what]').val());
      geo.geocode({address: $('input[name=where]').val()},
        function (results, gcstatus) {
          if (gcstatus == GM.GeocoderStatus.OK) {
            var viewport = results[0].geometry.viewport;
            _.map.fitBounds(viewport);
          } else {
            // Some kind of error occurred during geocoding request
          }
        });
      return false;
    });
  };
  _.init = function () {
    function initAfterMap() {
      _.initLivestream();
      _.initSearch();
      $(window).resize(_.resize);
      _.resize();
    }
    _.initMap($('#map').width('100%')[0], initAfterMap);
    _.initTags();
  };
  return _;
})();

$(LG.eye.init);
