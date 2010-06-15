// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function() {
  var cache = {};
  this.tmpl = function tmpl(str, data) {
    var fn = !/\W/.test(str) ?  cache[str] = cache[str] ||
      tmpl(document.getElementById(str).innerHTML) :
    new Function("obj",
      "var p=[],print=function(){p.push.apply(p,arguments);};" +
      "with(obj){p.push('" +
      str.replace(/[\r\t\n]/g, " ")
        .replace(/'(?=[^%]*%>)/g,"\t")
        .split("'").join("\\'")
        .split("\t").join("'")
        .replace(/<%=(.+?)%>/g, "',$1,'")
        .split("<%").join("');")
        .split("%>").join("p.push('")
      + "');}return p.join('');");
    return data ? fn(data) : fn;
  };
})();

function defaultTo(x, d) { return x ? x : d; }
function foldr(f, z, xs) {
  if (xs.length == 0) { return z; }
  return f(xs[0], foldr(f, z, xs.slice(1)));
};
function addPair(a, b) { return a + b; };
function sum(xs) { return foldr(addPair, 0, xs); };
const GM = google.maps;

var LG = defaultTo(LG, {});
LG.G = (function() {
var LGG = {};
LGG.setupExpandable = function () {
  $('.expandable').each(function () {
    var pane = this;
    var header = $('h1', pane);
    var content = $('.content', pane);
    function show() {
      header.animate({width: '98%'}, 'fast', function () {
        content.slideDown();
      });
      header.attr('expanded', 'true');
    }
    function hide() {
      content.slideUp(function () {
        header.animate({width: '200px'});
      });
      header.attr('expanded', 'false');
    }
    hide();
    header.click(function () {
      if (header.attr('expanded') == 'true') {
        hide();
      } else {
        show();
      }
    });
  });
};
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
  $('<li><a href="/api/photos/'+p.id+'"><img src="/photo/'+p.id+'/2" title="'+p.user.name+': '+p.name+'"/></a></li>')
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

LGG.dimmedDialog = function(jdom) {
  const HTML_DIMMER = '<div class="dialog dimmer"></div>';
  const HTML_TAB_CLOSE = '<img class="dialog close" src="/img/button_close.png" />';
  const dimmer = $(HTML_DIMMER);
  const close = $(HTML_TAB_CLOSE)
    .mousedown(function () { $(this).fadeTo('fast', 0.8); });
  const destroy = function () {
    dimmer.hide('fast');
    dimmer.detach();
    close.detach();
    jdom.detach();
  }
  jdom.addClass('dialog win');
  jdom.click(function (e) { e.stopPropagation(); });
  dimmer.appendTo('body').append(jdom).show();
  close.appendTo(jdom).click(destroy);
  //dimmer.click(destroy);
};

LGG.showWelcome = function () {
  const HTML_WELCOME = [
    '<div id="welcome">',
      '<h1 class="logo"><img src="/img/logo/medplus_no_tag.png" /></h1>',
      '<table>',
        '<tr>',
          '<td class="app">',
            '<h1><div class="whitecircle">1</div></h1>',
            '<h2>Download the App</h2>',
            '<a href="#"><img src="/img/welcome/app.png" /></a>',
            '<p>click the app to download</p>',
          '</td>',
          '<td class="connect">',
            '<h1><div class="whitecircle">2</div></h1>',
            '<h2>Sign in with any of these services</h2>',
          '</td>',
          '<td class="photos">',
            '<h1><div class="whitecircle">3</div></h1>',
            '<h2>Take photos!</h2>',
            "<h3>Photos on LiveGather show what you're doing and where you are!</h3>",
            '<h3><a href="#">Click here</a> to learn about fun ways to use LiveGather.</h3>',
          '</td>',
      '</table>',
    '</div>'].join('');
  const welcome = $(HTML_WELCOME);
  LGG.dimmedDialog(welcome);
};

LGG.showSigninPrompt = function(jdom) {
  const HTML_SIGNINUP = [
    '<div class="signinup">',
    '<ul><li><a href="#tab1"><span>Sign up</span></a></li>',
    '<li><a href="#tab2"><span>Sign in</span></a></li>',
    '</ul>',
    '</div>'].join('');
  const HTML_TAB_ONE = ['<div id="tab1">',
    '<div class="expandable step1">',
      '<h1>Tell us about you</h1>',
      '<div class="content">',
      '<a class="rpxnow" onclick="return false;" href="https://gather.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Flocalhost"> Sign In </a>',
      '</div>',
    '</div>',
    '<div class="expandable step2">',
      '<h1>Connect!</h1>',
      '<div class="content">',
         '<p>Connect to other social networking sites to have your photos automatically posted.</p>',
         '<table class="auths"><tr>',
         '<td><img src="/img/signup/connect_google.png" /></td>',
         '<td><img src="http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif\" /></td>',
         '</tr></table>',
      '</div>',
    '</div>',
     "</div>"].join('');
  const HTML_TAB_TWO = [
    '<div id="tab2" class="signin">',
      '<table>',
        '<tr>',
          '<td>',
            '<form method="POST" action="/sessions">',
            '<table>',
              '<tr><th class="header" colspan="2">Sign in with your LiveGather account</th></tr>',
              '<tr><th class="invalid" colspan="2">Incorrect username or password.</th></tr>',
              '<tr><th>Username</th>',
              '<td><input type="text" name="username" /></td>',
              '</tr>',
              '<tr><th>Password</th>',
              '<td><input type="password" name="password" /></td>',
              '</tr>',
              '<tr><th></th><td><input type="submit" value="Sign in"</td></tr>',
            '</table>',
            '</form>',
          '</td>',
          '<td class="or">or</td>',
          '<td class="auths">',
            '<ul>',
              '<li><img class="clickable" src="http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif" /></li>',
              '<li><img class="clickable" src="/img/signup/connect_google.png" /></li>',
            '</ul>',
          '</td>',
        '</tr>',
      '</table>',
      '</div>'].join('');
  var tabs = $(HTML_SIGNINUP);
  LGG.dimmedDialog(tabs);
  var tabSignup = $(HTML_TAB_ONE).appendTo(tabs);
  $('form', tabSignup).submit(function () {
    $.ajax({type: 'POST', url: '/users', dataType: 'json',
      data: $(this).serialize(),
      success: function (data) {
        $('.expandable.step1 h1', tabSignup).click();
        $('.expandable.step2 h1', tabSignup).click();
      },
      error: function () {
        $('.invalid', tabSignup).show();
      }});
    return false;
  });
  $('button', tabSignup).button().click(function () {
    $('form', tabSignup).submit();
  });
  $('.invalid', tabSignup).hide();
  var tabSignin = $(HTML_TAB_TWO).appendTo(tabs);
  tabSignin.submit(function () {
    $.ajax({type: 'POST', url: '/sessions', dataType: 'json',
      data: $('form', tabSignin).serialize(),
      success: function (data) {
        window.location = '/';
      },
      error: function () {
        // invalid credentials
        $('.invalid', tabSignin).show();
        $(':submit', tabSignin).attr('disabled', '');
      }
    });
    $(':submit', tabSignin).attr('disabled', 'disabled');
    return false;
  })
  $('input:first', tabSignin).focus();
  $('.invalid', tabSignin).hide();
  $(".birthday", tabs).datepicker({maxDate: "-13y", changeMonth: true,
                                   changeYear: true});
  tabs.tabs({selected: (jdom.hasClass('up') ? 0 : 1)});
  $('ul', tabs).removeClass('ui-corner-all').addClass('ui-corner-top');
  tabs.click(function (e) { e.stopPropagation(); });
  LGG.setupExpandable();
};

LGG.setupDefaultingInputFields = function (def) {
  /* Input fields with default values will automatically clear and restore the
   * default value when no user input is supplied. */
  $(':text')
    .live('focus', function() {
      var s = $(this);
      if (!s.data(def)) {
        s.data(def, s.val());
      }
      if (s.val() == s.data(def)) {
        s.val('').removeClass(def);
      }
    })
    .live('blur', function() {
      var s = $(this);
      if (s.val() == '') {
        s.val(s.data(def)).addClass(def);
      }
    });
};

LGG.init = function() {
  ///* JSify sign in */
  //$('.sign.in img').click(function () {
  //  LGG.showSigninPrompt($(this));
  //  return false;
  //});

  LGG.setupDefaultingInputFields('default');

  /* Setup headerStream */
  //LGG.headerStream = new LGG.HeaderStream($('#headerstream'));
  //$.getJSON('/api/photos/recent/'+Math.round(LGG.headerStream.getMaxPhotos()*1.5), function(photos) {
  //  if (photos.length < 1) {return;}
  //  var temp = [];
  //  for (i in photos) {
  //    temp.push(photos[i]);
  //  }
  //  photos = temp;
  //  while (LGG.headerStream.add(photos[0])) { photos.push(photos.shift()); }
  //  LGG.headerStream.ready = true;
  //  (function() { /* Cycle through the photos */
  //    LGG.headerStream.add(photos[0]);
  //    photos.push(photos.shift());
  //    setTimeout(arguments.callee, LGG.headerStream.timeBetween);
  //  })();
  //});
  ///* Setup detailed streams */
  //var classToState = {
  //  'comments': 'Comments',
  //  'map': 'Location',
  //  'meta': 'Tags'
  //};
  //$('.detailed.stream li .state').each(function() {
  //  var state = $('<p></p>');
  //  var jdom = $(this);
  //  for (var className in classToState) {
  //    (function(c) {
  //    jdom.append($('<div class="'+c+'"></div>').mouseover(function() {
  //      $(this).parent().parent().attr('class', c);
  //      state.html(classToState[c]);
  //    }));
  //    })(className);
  //  }
  //  jdom.append(state);
  //  state.html(classToState[$(this).parent().attr('class')]);
  //});
  //$('.detailed.stream li .detail.map .map').each(function() {
  //  var state = $(this).parent().parent().children('.state');
  //  state.children('.map').mouseover();
  //  var mapOpts = {
  //    zoom: 8,
  //    center: new google.maps.LatLng(-34.397, 150.644),
  //    mapTypeId: google.maps.MapTypeId.TERRAIN,
  //    scrollwheel: false,
  //    draggable: false,
  //    disableDefaultUI: true,
  //    mapTypeControl: false,
  //    navigationControl: false
  //  };
  //  var map = new google.maps.Map($(this)[0], mapOpts);
  //  var marker = new google.maps.Marker({position: map.getCenter(), map: map});
  //  setTimeout(function() { state.children('.comments').mouseover(); }, 150);
  //  $(this).css('position', 'absolute');
  //});

  // If the url has a welcome anchor show the welcome dialog.
  if (window.location.hash == '#welcome') {
    LGG.showWelcome();
  }
};
return LGG;
})();

$(LG.G.init);

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
'<div class="the_image s3"><img src="/photo/'+id+'" /></div>'+
'<p class="more"><a href="/api/photos/'+p.id+'">View full photo</a>'+
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
