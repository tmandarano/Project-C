LG.EXPLORE = {};
LG.EXPLORE.PEOPLE = {};

$(document).ready(function() {
  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    draggable: false,
    disableDoubleClickZoom: true,
    disableDefaultUI: true,
    mapTypeControl: false
  };
  LG.EXPLORE.PEOPLE.map = new google.maps.Map(
    $("#recently_near_people_map")[0], mapOpts);

  /* Select location */
  $('.location.selector')
  .css('cursor', 'pointer')
  .click(function() {
    if (!$(this).data('expanded')) {
      $(this).data('expanded', true);
      $(this).append($('<div>more choices</div>'));
    } else {
      $(this).data('expanded', false);
      $(this).children().remove();
    }
  });
});
