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
});
