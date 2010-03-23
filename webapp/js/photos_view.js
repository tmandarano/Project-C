var LG = LG ? LG : {};
LG.PICTURES = LG.PICTURES ? LG.PICTURES : {};
LG.PICTURES.VIEW = {};

function Actionbox(jdom) {
  var tools = ['/', '#', ')'];
  var toolbar = $('<div></div>');
  var activearea = $('<div></div>');
  jdom.append(toolbar);
  jdom.append(activearea);

  $.each(tools, function() {
    toolbar.append($('<div class="tool">'+this+'</div>'));
  });
  function setIn(jdom) {
    jdom.css({
      'background-color':'#d4e5ec',
      'color':'blue'
    });
  }
  function setOut(jdom) {
    jdom.css({
      'background-color':'transparent',
      'color':'black'
    });
  }
  $('.tool', toolbar).css({
    'width': '1.5em',
    'float': 'left',
    'cursor': 'pointer',
    'text-align': 'center'
  }).click(function() {
    $(this).css('color', 'blue');
    activearea.show();
  }).hover(function() { setIn($(this)); },
           function() { setOut($(this)); }
  );
  toolbar.css({
    'height': '1em',
    'color': '#999'
  });
  activearea.css({
    'width': '100%',
    'height': '1.5em',
    'border-color': '#999',
    'border-style': 'solid',
    'border-width': '1px'
  }).hide();
};

LG.PICTURES.VIEW.init = function() {
  Actionbox($('.actionbox'));
  var mapOpts = {
    zoom: 7,
    center: new google.maps.LatLng(32.77977,-117.137947),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    scrollwheel: false,
    draggable: false,
    disableDefaultUI: true,
    mapTypeControl: false,
    navigationControl: false
  };
  var map = LG.PICTURES.VIEW.map = new google.maps.Map($("#map_location")[0], mapOpts);
  $('#map_location').css({'width': '250px', 'height': '250px'});
  LG.PICTURES.VIEW.marker = new google.maps.Marker({position: map.getCenter(), map: map});
};
$(document).ready(LG.PICTURES.VIEW.init);
