$(document).ready(function() {
  var classToState = {
    'comments': 'Comments',
    'map': 'Location',
    'meta': 'Tags'
  };
  $('ol.stream li .state').each(function() {
    var state = $('<p></p>');
    var jdom = $(this);
    for (var className in classToState) {
      (function(c) {
      jdom.append($('<div class="'+c+'"></div>').mouseover(function() {
        $(this).parent().parent().attr('class', c);
        state.html(classToState[c]);
      }));
      })(className);
    }
    jdom.append(state);
    state.html(classToState[$(this).parent().attr('class')]);
  });
});
