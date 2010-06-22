LG.share = defaultTo(LG.share, {});
LG.share.upload = (function () {
  var mapOpts = {
    zoom: 7,
    center: new GM.LatLng(32.77977,-117.137947),
    mapTypeId: GM.MapTypeId.TERRAIN,
  };

  var _ = {};
  _.init = function () {
    var map = _.map = new GM.Map($("#map")[0], mapOpts);
    _.marker = new GM.Marker({
      draggable: true,
      position: _.map.getCenter(),
      map: _.map
    });
    _.marker.bindTo('position', _.map, 'center');

    _.follower = (function () {
      var x = function () {};
      var xp = x.prototype = new GM.MVCObject();
      xp.position_changed = function () {
        var latlng = this.get('position');
        $('#lng').val(latlng.lng());
        $('#lat').val(latlng.lat());
      };
      var y = new x();
      y.bindTo('position', _.marker);
      return y;
    })();

    GM.event.addListener(map, 'click', function(e) {
      _.marker.setPosition(e.latLng);
    });
  };
  return _;
})();
$(function () {
  LG.share.upload.init();
  $('#photo_caption').val('caption yay');
  $('#photo_tags').val('tag is more, than two words long');
  var uploader = new AjaxUpload('#photo_photo', {
    action: '/upload.php',
    name: 'userfile',
    autoSubmit: false,
    onComplete: function (file) {
      var data = $('form').serialize() + '&userfile=' + file;
      $.ajax({
        type: 'POST',
        url: '/api/photos',
        data: data,
        dateType: 'json',
        success: function (result) {
          alert('yay');
        },
        error: function () {
          alert('unable to save');
        }
      });
    }
  });
  $('#photo_submit_share').click(function () {
      console.log('clicked');
    uploader.submit();
      console.log('done');
    return false;
  });
});
