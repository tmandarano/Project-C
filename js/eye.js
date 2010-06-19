LG.map = defaultTo(LG.map, {});
LG.map.defaultLoc = new GM.LatLng(32.7155, -117.1636); // San Diego 1st and Broadway
LG.map.markers = defaultTo(LG.map.markers, {});
LG.map.markers.photo = function (photo) {
  var marker = null;
  $.ajax({async: false, url: ['/api/photos/', photo.id, '/user'].join(''),
    dataType: 'json',
    success: function (user) {
      marker = new GM.Marker({
        position: new GM.LatLng(photo.latitude, photo.longitude),
        title: [user.username, ': ', photo.caption].join(''),
        icon: new GM.MarkerImage(['api/photo/', photo.id, '/1'].join(''), null, null, new GM.Point(20, 42)),
        shadow: new GM.MarkerImage('/img/mapmkrbdr.png')
      });
    }
  });
  return marker;
};

LG.eye = (function () {
  var _ = {
    map: null
  };
  _.doSearch = function (q) {
    console.log('searching for', q);
    // TODO waiting for API
    // TODO for real
    var jitter = function () { return Math.random() - 0.5; };
    _.addPhoto({id:112,name:null,latitude:32 + jitter(),longitude:-117 + jitter(),caption:"a",tags:[{id:33,tag:"tag1"}]});
    _.addPhoto({id:112,name:null,latitude:32 + jitter(),longitude:-118 + jitter(),caption:"b",tags:[{id:33,tag:"tag1"}]});
    _.addPhoto({id:112,name:null,latitude:33 + jitter(),longitude:-118 + jitter(),caption:"c",tags:[{id:33,tag:"tag1"}]});
    _.addPhoto({id:112,name:null,latitude:33 + jitter(),longitude:-117 + jitter(),caption:"d",tags:[{id:33,tag:"tag1"}]});
    _.addPhoto({id:112,name:null,latitude:32.1 + jitter(),longitude:-117.1 + jitter(),caption:"e",tags:[{id:33,tag:"tag1"}]});

  };
  _.addPhoto = function (photo) {
    var marker = LG.map.markers.photo(photo);
    marker.setMap(_.map);
    GM.event.addListener(marker, 'click', function () {
      LG.G.showPhoto(photo.id);
      return false;
    });
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
  _.initMap = function (div) {
    var latlng = defaultTo(LG.loc.get(), LG.map.defaultLoc);
    var mapOpts = {
      zoom: 7,
      center: latlng,
      mapTypeId: GM.MapTypeId.TERRAIN,
    };
    _.map = new GM.Map(div, mapOpts);
  };
  _.resizeMap = function () {
    // Resize map so the livestream is always at the bottom.
    var outerHeight = function (e) { return $(e).outerHeight(); };
    $('#map').height(Math.max(0, $(window).height() - 
      sum($.map(['#header', '#trendingtags', '#livestream', '#softener'],
            outerHeight)) - 8));
    GM.event.trigger(_.map, 'resize');
  };
  _.initLivestream = function () {
    var alllive = $('#livestream ol');
    LG.G.fade(alllive.parent());

    function addPhoto(id) {
      alllive.append(
        $('<li></li>').append(
          $(['<a href="#"><img src="/api/photo/', id, '/3" /></a>'].join(''))
            .click(function () { LG.G.showPhoto(id); return false; })));
    }

    $.get('/api/photos/recent/20', function (photos) {
      for (var i in photos) {
        addPhoto(photos[i].id);
      }
    }, 'json');

    alllive.hide();
    alllive.fadeIn('fast');
  };
  _.initSearch = function () {
    $('#search form').submit(function () {
      _.doSearch($('input', this).val());
      return false;
    });
  };
  _.init = function () {
    _.initMap($('#map').width('100%')[0]);
    _.initTags();
    _.initLivestream();
    _.initSearch();
    $(window).resize(_.resizeMap);
    _.resizeMap();
  };
  return _;
})();

$(LG.eye.init);
