var LG = LG ? LG: {};
LG.G = (function() {
var LGG = {};
LGG.HeaderStream = (function() {
var H = function(jdom) {
  this.jdom = jdom;
  this.timeBetween = 6000;
  this.slideWidth = 50;
  this.fadeTime = 400;
  (function(self) { $(window).resize(function() {
    self.sync();
  }); })(this);
  this.sync();
  this.ready = false;
};
var HP = H.prototype = function() {};
HP.append = function(json) {
  var p = json;
  p.location="location";
  p.lat = 32;
  p.lng = -117;
  p.user = {name: 'name'}; // TODO
  $('<li><a href="/photos/view/'+p.id+'"><img src="/images/IMG'+p.id+'.jpg" title="'+p.user.name+': '+p.name+'"/></a></li>')
    .data('json', json)
    .appendTo(this.jdom);
  if (this.ready) {
    this.jdom.trigger('change');
  }
};
HP.getMaxPhotos = function() { return Math.floor(this.jdom.width() / this.slideWidth); };
HP.getNumPhotos = function() { return this.jdom.children().length; };
HP.isOverFull = function() { return this.getNumPhotos() > this.getMaxPhotos(); };
HP.isFull = function() { return this.getNumPhotos() == this.getMaxPhotos(); };
HP.sync = function() {
  var padding = (this.jdom.width()-this.getMaxPhotos()*this.slideWidth)/2;
  this.jdom.css('padding-left', padding);
  while (this.isOverFull()) {
    this.jdom.children(':first').remove();
  }
};
/* returns true if the stream is not full yet */
HP.add = function(photo) {
  if (this.isFull()) {
    this.jdom.children(':first').hide(this.fadeTime, function() { $(this).remove(); });
    this.append(photo);
    return false;
  } else {
    this.append(photo);
    return !this.isFull();
  }
};
return H;
})();

/* ExploreStreams are the small 10 photo horizontal streams that have icons to
 * change the stream type. The actual workings need to be clarified. */
LGG.ExploreStream = (function() {
var E = function(jdom) {
  this.jdom = jdom;
};

return E;
});

/* DetailStreams are the large type of stream showing the photo and three tabs
 * of info: comments, location, and tags. TODO what happens when there are
 * more than one comments? Other info that might overflow? */
LGG.DetailStream = (function() {
var D = function(jdom) {
  this.jdom = jdom;
};

return D;
});

LGG.showSigninPrompt = function(jdom) {
  var dimmer = $('<div class="dimmer"></div>').css('top', 0)
    .appendTo('body');
  (function(self) { $(window).resize((function() {
    self.width($('body').width()).height($('body').height());
  })()); })(dimmer);
  var tabs = $(["<div class=\"signinup\">",
                "<ul><li><a href='#tab1'><span>Sign in</span></a></li>",
                "<li><a href='#tab2'><span>Sign up</span></a></li>",
                "</ul>",
                "</div>"].join(''))
    .css({'margin': 'auto', 'background-color': 'white',
          'border': '0.7em solid #91cf55', 'width': 580,
          "position": "relative", "top": "5em",
          'border-radius': '1.4em',
          '-webkit-border-radius': '1.4em',
          '-moz-border-radius': '1.4em'}).appendTo(dimmer);
  var close = $('<img src="/img/button_close.png" />').appendTo(tabs)
    .css({"position": "absolute", "top": "-24px",
          "right": "-24px", "cursor": "pointer"});
  var form = $([
    '<div id="tab1">',
    '<table class="auths">',
      '<tr>',
        "<td><img src=\"/img/signup/connect_google.png\" /></td>",
        "<td><img src=\"http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif\" /></td>",
      '</tr>',
    '</table>',
    '<form id="SigninForm" method="post" action="/users/login">',
    '<table>',
      '<tr><th>Email</th>',
      '<td><input type="text" name="data[User][email]" id="UserEmail" /></td>',
      '</tr>',
      '<tr><th>Password</th>',
      '<td><input type="password" name="data[User][password]" id="UserPassword" /></td>',
      '</tr>',
      '<tr><th></th><td><input type="submit" value="Sign in"</td></tr>',
      '</table></form></div>'].join('')).appendTo(tabs);
  $(["<div id=\"tab2\">",
     "<h1><strong>Step 1.</strong> Create an account using your Google Account or Facebook information.</h1>",
     "<table class=\"auths\"><tr>",
     "<td><img src=\"/img/signup/connect_google.png\" /></td>",
     "<td><img src=\"http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif\" /></td>",
     "<td><a href=\"\">Direct Sign Up</a></td>",
     "</tr></table>",
     "<h1><strong>Step 2.</strong> Fill in the missing information.</h1>",
     "<form action=\"/\">",
     "<div class=\"info\">",
     "<div class=\"userphoto\">",
       "<h1>Profile Photo</h1>",
       "<img src=\"/img/50x50.jpg\" /><br />",
       "<p><a href=\"#\">Choose a photo</a></p>",
     "</div>",
     "<table>",
     "<tr><th>Display name</th><td><input type=\"text\" /></td>",
     "<tr><th>       Email</th><td><input type=\"text\" /></td>",
     "<tr><th>    Birthday</th><td><input class=\"birthday\" type=\"text\" /></td>",
     "<tr><th>    Location</th><td><input type=\"text\" /></td>",
     "</table>",
     "</div>",
     "<p><input name=\"privacy\" type=\"checkbox\" />",
       "<label for=\"privacy\">I agree to the LiveGather's ",
       "<a href=\"\">privacy policy</a> and ",
       "<a href=\"\">terms and conditions</a></label></p>",
     "<p><input name=\"email\" type=\"checkbox\" />",
       "<label for=\"email\">I am ok with receiving emails from LiveGather",
       "</label></p>",
     "<input type=\"image\" src=\"/img/signup/create.png\" />",
     "</form>",
     "</div>"].join('')).appendTo(tabs);
  $(".birthday", tabs).datepicker({maxDate: "-13y", changeMonth: true,
                                   changeYear: true});
  if (jdom.hasClass('up')) {
    tabs.tabs({selected: 1});
  } else {
    tabs.tabs();
  }
  $('ul', tabs).removeClass('ui-corner-all').addClass('ui-corner-top');
  $('#UserEmail', form).focus();
  function destroy() { dimmer.remove(); }
  tabs.click(function (e) { e.stopPropagation(); });
  close.click(destroy);
  dimmer.click(destroy);
};

LGG.init = function() {
  /* JSify sign in */
  $('.sign.in img').click(function () {
    LGG.showSigninPrompt($(this));
    return false;
  });
  /* Default form clearing */
  var def = 'default';
  $(':text')
    .live('focus', function() {
      if (!$(this).data(def)) { $(this).data(def, $(this).val());}
      if ($(this).val() == $(this).data(def)) { $(this).val('').removeClass(def); }
    })
    .live('blur', function() {
      if ($(this).val() == '') {
        $(this).val($(this).data(def)).addClass(def);
      }
    })

  /* Setup headerStream */
  LGG.headerStream = new LGG.HeaderStream($('#headerstream'));
  $.getJSON('/photos/recent/'+Math.round(LGG.headerStream.getMaxPhotos()*1.5), function(photos) {
    if (photos.length < 1) {return;}
    var temp = [];
    for (i in photos) {
      temp.push(photos[i]);
    }
    photos = temp;
    while (LGG.headerStream.add(photos[0])) { photos.push(photos.shift()); }
    LGG.headerStream.ready = true;
    (function() { /* Cycle through the photos */
      LGG.headerStream.add(photos[0]);
      photos.push(photos.shift());
      setTimeout(arguments.callee, LGG.headerStream.timeBetween);
    })();
  });
  /* Setup detailed streams */
  var classToState = {
    'comments': 'Comments',
    'map': 'Location',
    'meta': 'Tags'
  };
  $('.detailed.stream li .state').each(function() {
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
  $('.detailed.stream li .detail.map .map').each(function() {
    var state = $(this).parent().parent().children('.state');
    state.children('.map').mouseover();
    var mapOpts = {
      zoom: 8,
      center: new google.maps.LatLng(-34.397, 150.644),
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      scrollwheel: false,
      draggable: false,
      disableDefaultUI: true,
      mapTypeControl: false,
      navigationControl: false
    };
    var map = new google.maps.Map($(this)[0], mapOpts);
    var marker = new google.maps.Marker({position: map.getCenter(), map: map});
    setTimeout(function() { state.children('.comments').mouseover(); }, 150);
    $(this).css('position', 'absolute');
  });
};
return LGG;
})();

function viewpic(id) {
  var dimmer = $('<div id="viewpic_dimmer" class="dimmer"></div>')
    .css('top', $('#header').height())
    .height($('body').height()-$('#header').height())
    .prependTo('body');
  var close = $('<a id="viewpic_close" href="#">Close</a>');
  var viewer = $('<div id="viewpic"></div>')
    .append(close)
    .prependTo('#viewpic_dimmer');
  var xhr = $.getJSON('/photos/show/'+id, function(json) {
    var p = json;
    p.location = "location";
    p.lat = 32;
    p.lng = -117;
    p.user = {'id': 1, 'name': 'name'}; // TODO
    var u = p.user;
    var display = '<table class="split"><tr>'+
'<td class="left pane">'+
'<div class="users">'+
'<a href="/profile/'+u.id+'"><img src="/users/photo/'+u.id+'" /></a>'+
'<a href="/profile/'+u.id+'" class="username">'+u.name+'</a> '+
'<span class="location">'+p.location+'</span> '+
'<span class="time">'+p.datetime+'</span>'+
'<p class="caption">crazy wildfires in LA!</p>'+
'</div>'+
'<div class="the_image s3"><img src="/photos/'+id+'" /></div>'+
'<p class="more"><a href="/photos/view/'+p.id+'">View full photo</a>'+
"</td>"+
'<td class="right pane"><p><a href="#">Share to Facebook</a></p><p><a href="#">Share to Twitter</a>'+
'<div class="similar"><h1 class="bubble">Similar pictures nearby</h1>'+
'<img src="/photos/'+3+'" />'+'<img src="/photos/'+5+'" />'+'<img src="/photos/'+'fire_d'+'" />'+"<br />"+
'<img src="/photos/'+'fire_e'+'" />'+'<img src="/photos/'+'fire_f'+'" />'+'<img src="/photos/'+'fire_g'+'" />'+"</div>"+
'<div id="viewpic_map"></div></td></tr></table>';
    viewer.prepend(display);
    var mapOpts = {
      zoom: 7,
      center: new google.maps.LatLng(p.lat, p.lng),
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      scrollwheel: false,
      draggable: false,
      disableDefaultUI: true,
      mapTypeControl: false,
      navigationControl: false
    };
    var map = new google.maps.Map($("#viewpic_map")[0], mapOpts);
    var marker = new google.maps.Marker({position: map.getCenter(), map: map});
  });
  function destroy() {
    xhr.abort();
    dimmer.remove();
  }
  dimmer.click(destroy);
  viewer.click(function(e) {e.stopPropagation();});
  close.click(destroy);
}

$(LG.G.init);
