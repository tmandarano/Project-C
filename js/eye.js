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
      search = null,
      latentResizeRefresh = null;
  var updateTimer = null,
      refreshDelay = 1234800,//8000
      UPDATING = false,
      floodTime = 200; // long enough for timeout to cancel when resizing

  var Livestream = (function () {
    var L = {};

    var alllive = null;

    L.init = function () {
      alllive = $('#livestream ol');
      alllive.hide().fadeIn('fast');
    };
    L.clear = function () {
      alllive.empty();
    };
    L.addPhoto = function (photo) {
      var id = photo.id;
      $.get('/api/photos/' + id + '/user', function (user) {
        var photoInfo = $(['<li><img src="/api/photos/', id, '/3" />',
           '<div class="clickable">',
           '<p class="time">', LG.dateToVernacular(photo.date_added), '</p>',
           '<p class="user">', user ? user.username : 'Unknown user', '</p>',
           '<p class="location">', photo.location || 'Unknown place', '</p>',
           '</div></li>'
          ].join(''));
        photoInfo.find('div')
          .click(function () { LG.G.showPhoto(id); })
          .hover(
             function () {
               $(this).find('span').fadeTo('fast', 1);
             },
             function () {
               $(this).find('span').fadeTo('fast', 0.5);
             }
           );
        photoInfo.find('p').each(function () {
          var html = $(this).html();
          $(this).empty().append(
            $('<span></span>').html(html).fadeTo('slow', 0.5));
        });
        alllive.append(photoInfo);
      }, 'json');
    }

    return L;
  })();
  var PhotosOverlay = (function () {
    var lastPhotos = [],
        ptcache = [],
        ptcacheMax = 10000,
        idleDraw = null;
    function coordkey(x, y) {
      return x + ',' + y;
    }
    function cellToStr(cell) {
      return $.map(cell, function (c) {
        return [c.lat().toFixed(5), c.lng().toFixed(5)].join(',');
      }).join(';');
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
    function getAndShowPhotoForCell(cell, last) {
      $.get('/api/photos/area/1/' + cellToStr(cell), function (photos) {
        if (photos.length > 0) {
          var photo = photos[0];
          lastPhotos.push(addPhoto(photo));
          Livestream.addPhoto(photo);
        }
        if (last) {
          UPDATING = false;
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
      idleDraw = GM.event.addListenerOnce(this.get('map'), 'idle', doSearch);
    };
    Pp.onRemove = function () {
      while (lastPhotos.length > 0) {
        var photo = lastPhotos.pop();
        if (photo) { photo.setMap(null); }
      }
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
        .fromDivPixelToLatLng(new GM.Point(x, y));
    };
    Pp.doSearch = function () {
      if (!this.get('map') || !this.get('projection')) {
        return;
      }
      this.onRemove();

      var size = 40 * 3;
      var div = $(this.get('map').getDiv());
      var width = div.outerWidth(),
          height = div.outerHeight();

      // clear the point cache for new projection
      this._coord(0, 0, true);

      for (var x = 0; x < width; x += size) {
        for (var y = 0; y < height; y += size) {
          getAndShowPhotoForCell(
            [this._coord(x, y + size), this._coord(x + size, y)],
            (x + size) >= width && (y + size) >= height);
        }
      }
    };
    return new P();
  })();
  var Tags = (function () {
    var T = {};
    var alltags = null;
    var headertag = null;

    function showNowTrendingTags() {
      $.get('/api/tags/trending/20', function (tags) {
        $.map(tags, function (t) {
          var tag = t.tag;
          alltags.append($(['<a href="#">', tag, '</a> '].join(''))
              .click(function () {
                setSearch(tag);
                return false;
              }))
        });
      }, 'json');
    }

    T.init = function () {
      alltags = $('#trendingtags').hide();

      showNowTrendingTags();
      alltags.fadeIn('fast');
    };
    return T;
  })();

  function doSearch() {
    if (UPDATING) {
      return;
    }
    console.log('searching for', search);
    UPDATING = true;
    if (updateTimer) {
      clearTimeout(updateTimer);
    }
    Livestream.clear();
    PhotosOverlay.doSearch();
      
    updateTimer = setTimeout(doSearch, refreshDelay);
  }
  function setSearch(q) {
    search = q;
    doSearch();
  }

  function resize() {
    // Resize map so the livestream is always at the bottom.
    var outerHeight = function (e) { return $(e).outerHeight(); };
    var contentHeight = Math.max(0, $(window).height() - 
      sum($.map(['#header', '#headerstream', '#footer'],
                outerHeight)));
    var sidebarWidth = 400;
    $('#current').width(sidebarWidth);
    $('#map').height(contentHeight).width($('#content').width() - sidebarWidth);
    $('#trendingtags').width(sidebarWidth);
    $('#livestream').height(contentHeight - $('#trendingtags').outerHeight())
                    .width(sidebarWidth);
    GM.event.trigger(map, 'resize');

    clearTimeout(latentResizeRefresh);
    latentResizeRefresh = setTimeout(doSearch, floodTime);
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
  function initSearch() {
    var geo = new GM.Geocoder();
    var lastWhere = null;
    $('#search form').submit(function () {
      try {
        setSearch($('input[name=what]').val());
        var where = $('input[name=where]').val();
        if (lastWhere != where) {
          geo.geocode({address: where}, function (results, gcstatus) {
            if (gcstatus == GM.GeocoderStatus.OK) {
              var viewport = results[0].geometry.viewport;
              map.fitBounds(viewport);
            } else {
              // Some kind of error occurred during geocoding request
            }
          });
        }
        lastWhere = where;
      } catch (e) {
        console.log(e);
      }
      return false;
    });
  }
  function init() {
    Tags.init();
    function initAfterMap() {
      Livestream.init();
      initSearch();
      $(window).resize(resize);
      resize();
    }
    initMap($('#map').width('100%')[0], initAfterMap);
  }
  return init;
})();

$(LG.eye);
