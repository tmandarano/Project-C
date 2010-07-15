LG.map = defaultTo(LG.map, {});
LG.map.defaultLoc = new GM.LatLng(32.7155, -117.1636); // San Diego 1st and Broadway

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
  var map = null,
      updateTimer = null,
      idleDraw = null,
      search = null,
      latentResizeRefresh = null,
      refreshDelay = 8000,
      floodTime = 200; // long enough for timeout to cancel when resizing
  var alllive = null;
  var PhotosOverlay = (function () {
    var lastPhotos = [],
        ptcache = [],
        ptcacheMax = 10000,
        UPDATING = false,
        floodGuardTimer = null;
    function getDefaultSearcher(self) {
      return function () { self.doSearch(); };
    }
    function coordkey(x, y) {
      return x + ',' + y;
    }
    function cellToStr(cell) {
      return $.map(cell, function (c) {
        return [c.lat().toFixed(5), c.lng().toFixed(5)].join(',');
      }).join(';');
    }
    function floodGuard() {
      floodGuardTimer = setTimeout(function () {
        UPDATING = false;
      }, floodTime);
    }
    function addPhoto(photo) {
      var marker = LG.map.markers.photo(photo);
      if (marker) {
        marker.setMap(map);
        GM.event.addListener(marker, 'click', function () {
          LG.G.showPhoto(photo.id);
          return false;
        });
      }
      return marker;
    }
    function getAndShowPhotoForCell(cell) {
      $.get('/api/photos/area/1/' + cellToStr(cell), function (photos) {
        if (photos.length > 0) {
          var photo = photos[0];
          lastPhotos.push(addPhoto(photo));
        }
      }, 'json');
    }

    function P() {}
    var Pp = P.prototype = new GM.OverlayView();
    Pp.onAdd = function () {
      var draw = (function (self) { return function () { self.draw(); }; })(this);
      GM.event.addListener(this.get('map'), 'dragend', draw);
    };
    Pp.draw = function () {
      if (idleDraw) {
        GM.event.removeListener(idleDraw);
      }
      idleDraw = GM.event.addListenerOnce(this.get('map'), 'idle',
        getDefaultSearcher(this));
    };
    Pp.onRemove = function () {
      while (lastPhotos.length > 0) {
        lastPhotos.pop().setMap(null);
      }
    };
    Pp.setSearch = function (q) {
      search = q;
      getDefaultSearcher(this);
    };
    Pp._coord = function (x, y, clear) {
      if (clear) {
        ptcache = [];
      }
      var k = coordkey(x, y);
      var val = ptcache[k];
      if (val) {
        return val;
      }
      if (ptcache.length > ptcacheMax) {
        ptcache = [];
      }
      return ptcache[k] = this.getProjection()
        .fromContainerPixelToLatLng(new GM.Point(x, y));
    };
    Pp.doSearch = function () {
      if (UPDATING) {
        if (floodGuardTimer) {
          clearTimeout(floodGuardTimer);
        }
        floodGuard();
        return;
      }
      if (!this.get('map') || !this.get('projection')) {
        return;
      }
      UPDATING = true;
      if (updateTimer) {
        clearTimeout(updateTimer);
      }
      this.onRemove();
      console.log('searching for', search);

      var size = 40 * 3;
      var div = $(this.get('map').getDiv());
      var width = div.outerWidth(),
          height = div.outerHeight();

      for (var x = 0; x < width; x += size) {
        for (var y = 0; y < height; y += size) {
          // nw, ne, se, sw
          getAndShowPhotoForCell([
            this._coord(x, y), this._coord(x + size, y),
            this._coord(x + size, y + size), this._coord(x, y + size)]);
        }
      }
      
      updateTimer = setTimeout(getDefaultSearcher(this), refreshDelay);
      floodGuard();
    };
    return new P();
  })();
  function initTags() {
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
                PhotosOverlay.setSearch('tag:'+tags[i]); TODO
                return false;
              })));
        }
      }, 'json');
    }

    alltags.append(headertag).hide();

    showNowTrendingTags();
    //setTimeout(showNowTrendingTags, 60000);
    alltags.fadeIn('fast');
  }
  function initMap(div, initAfterMap) {
    LG.loc.get(function (latlng) {
      latlng = defaultTo(latlng, LG.map.defaultLoc);
      console.log('center on', latlng.lat(), latlng.lng());
      var mapOpts = {
        zoom: 13,
        center: latlng,
        mapTypeId: GM.MapTypeId.ROADMAP,
      };
      map = new GM.Map(div, mapOpts);
      PhotosOverlay.setMap(map);
      initAfterMap();
    });
  }
  function resize() {
    // Resize map so the livestream is always at the bottom.
    var outerHeight = function (e) { return $(e).outerHeight(); };
    var contentHeight = Math.max(0, $(window).height() - 
      sum($.map(['#header', '#trendingtags', '#footer'], outerHeight)));
    var sidebarWidth = 300;
    $('#map').height(contentHeight).width($('#content').width() - sidebarWidth);
    $('#livestream').height(contentHeight).width(sidebarWidth);
    GM.event.trigger(map, 'resize');

    clearTimeout(latentResizeRefresh);
    latentResizeRefresh = setTimeout(function () {
      PhotosOverlay.doSearch();
    }, floodTime);
  }
  function addPhotoLivestream(photo) {
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

      alllive.append(photoInfo);
    }, 'json');
  }
  function initLivestream() {
    alllive = $('#livestream ol');

    $.get('/api/photos/recent/20', function (photos) {
      for (var i in photos) {
        addPhotoLivestream(photos[i]);
      }
    }, 'json');

    alllive.hide().fadeIn('fast');
  }
  function initSearch() {
    var geo = new GM.Geocoder();
    $('#search form').submit(function () {
      PhotosOverlay.setSearch($('input[name=what]').val());
      var where = $('input[name=where]').val();
      if (_.lastWhere != where) {
        geo.geocode({address: where}, function (results, gcstatus) {
          if (gcstatus == GM.GeocoderStatus.OK) {
            var viewport = results[0].geometry.viewport;
            map.fitBounds(viewport);
          } else {
            // Some kind of error occurred during geocoding request
          }
        });
      }
      _.lastWhere = where;
      return false;
    });
  }
  function init() {
    function initAfterMap() {
      initLivestream();
      initSearch();
      $(window).resize(resize);
      resize();
    }
    initMap($('#map').width('100%')[0], initAfterMap);
    initTags();
  }
  return init;
})();

$(LG.eye);
