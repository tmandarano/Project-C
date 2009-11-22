var PROJC = PROJC ? PROJC : {};
PROJC.html = {};
PROJC.html.img = {};
PROJC.html.img.user = function(id) {
  return "<img src=\"img/user/"+id+".jpg\" />";
};
PROJC.html.img.db = function(id) {
  return "<img src=\"pictures/"+id+"\" />";
};

function viewpic(id) {
  /* TODO eventually get this data from the server */
  var json = {display: "<table><tr>"+
"<td>"+PROJC.html.img.user('bigtiger23')+"<a href=\"#\">bigtiger23</a><p>crazy wildfires in LA!</p><p class=\"location\">Los Angeles, CA</p><p class=\"time\">30 minutes ago</p>"+PROJC.html.img.db(id)+"<p><a href=\"#\">View comments</a></td>"+
"<td><p><a href=\"#\">Share to Facebook</a></p><p><a href=\"#\">Share to Twitter</a>"+
"<div class=\"similar\"><h1>Similar pictures nearby</h1>"+PROJC.html.img.db(3)+PROJC.html.img.db(5)+PROJC.html.img.db('fire_d')+"<br />"+PROJC.html.img.db('fire_e')+PROJC.html.img.db('fire_f')+PROJC.html.img.db('fire_g')+"</div>"+
"<div id=\"map_location\"></div></td></tr></table>", location: new google.maps.LatLng(34.081237,-118.299127)};

  $('#content').prepend('<div id="viewpic_dimmer"></div>');
  var content_height = $('#content').height();
  $('#viewpic_dimmer').css({'height': content_height+'px'});
  $('#viewpic_dimmer').prepend('<div id="viewpic">'+json.display+'</div>');
  $('#viewpic').css({'top': (content_height/2-$('#viewpic').height()/2)+'px'});
  $('#viewpic').append('<div id="viewpic_close"><a href="#" onclick="viewpic_close();">Close</a></div>');
}
function viewpic_close() {
  $('#viewpic_dimmer').remove();
}
