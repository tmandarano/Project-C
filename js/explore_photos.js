function photo_stub_explore_stream(jdom, photo_id) {
  jdom.html('<img src="/img/loading.gif" />');
  $.get('/shard/photo_stub_explore_stream/'+photo_id, function(data) {
    jdom.html(data);
  });
}
$(function() {
  var numRecent = 8;
  var numViewed = 4;
  var numLiked = 4;
  var recent = $('#most_recent .exp.stream');
  for (var i=0; i<numRecent; i++) {
    var li = $('<li></li>');
    recent.append(li);
    photo_stub_explore_stream(li, 451);
  }
  var viewed = $('#most_viewed .exp.stream');
  for (var i=0; i<numViewed; i++) {
    var li = $('<li></li>');
    viewed.append(li);
    photo_stub_explore_stream(li, 451);
  }
  var liked = $('#most_liked .exp.stream');
  for (var i=0; i<numLiked; i++) {
    var li = $('<li></li>');
    liked.append(li);
    photo_stub_explore_stream(li, 451);
  }
});
