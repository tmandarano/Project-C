$(document).ready(function() {
  $('ol.stream li .state').each(function() {
    $(this)
    .append($('<div class="comments"></div>').mouseover(function() {$(this).parent().parent().attr('class', 'comments');}))
    .append($('<div class="map"></div>')     .mouseover(function() {$(this).parent().parent().attr('class', 'map');}))
    .append($('<div class="meta"></div>')    .mouseover(function() {$(this).parent().parent().attr('class', 'meta');}));
  });
});
