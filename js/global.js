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
var GM = google.maps;

var console = defaultTo(window.console, {
  log: function () {}
});

var LG = defaultTo(LG, {});
LG.dateToVernacular = function (str) {
  function pluralize(one, order) {
    if (order == 1) {
      return one;
    }
    return one + 's';
  }
  var dat = new Date(str);
  var now = new Date();

  function doD(fn, eq, t) {
    var n = fn.apply(now);
    var d = fn.apply(dat);
    var delt = n - d;
    if (delt == 0) {
      return eq();
    } else {
      if (delt > 0) {
        return [delt, pluralize(t, delt), 'ago'].join(' ');
      } else {
        return 'Huh? ' + t;
      }
    }
  }

  return doD(Date.prototype.getFullYear, function () {
    return doD(Date.prototype.getMonth, function () {
      return doD(Date.prototype.getDay, function () {
        return doD(Date.prototype.getHours, function () {
          return doD(Date.prototype.getMinutes, function () {
            return doD(Date.prototype.getSeconds, function () {
              return 'now';
            }, 'second');
          }, 'minute');
        }, 'hour');
      }, 'day');
    }, 'month');
  }, 'year');
};
LG.G = (function () {
var LGG = {};
LGG.html = {};
LGG.html.collage = {};
LGG.html.collage.photos = function (jdom, photo_ids) {
  jdom.addClass('collage');
  for (var i in photo_ids) {
    $(['<li><a href="/photos/view/', photo_ids[i], '"><img src="/api/photo/', 
      photo_ids[i], '/1" /></a></li>'].join('')).appendTo(jdom);
  }
};
LGG.html.collage.people = function (jdom, people_ids) {
  jdom.addClass('collage');
  for (var i in people_ids) {
    $(['<li><a href="/profile/', people_ids[i], '"><img src="/api/users/photo/', 
      people_ids[i], '" /></a></li>'].join('')).appendTo(jdom);
  }
};
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
var _ = function(jdom) {
  this.jdom = jdom;
  this.timeBetween = 6000;
  this.slideWidth = 50;
  this.fadeTime = 400;
  (function(self) { $(window).resize(function() {
    self.sync();
  }); })(this);
  this.sync();
  this.ready = false;
  this.init();
};
var _p = _.prototype = function() {};
_p.append = function (json) {
  var p = json;
  p.location="location";
  p.lat = 32;
  p.lng = -117;
  p.user = {name: 'name'}; // TODO
  $(['<li><a href="/api/photos/', p.id, '"><img src="/photo/', p.id,
     '/2" title="', p.user.name, ': ', p.name, '"/></a></li>'].join(''))
    .data('json', json)
    .appendTo(this.jdom);
  if (this.ready) {
    this.jdom.trigger('change');
  }
};
_p.getMaxPhotos = function () { return Math.floor(this.jdom.width() / this.slideWidth); };
_p.getNumPhotos = function () { return this.jdom.children().length; };
_p.isOverFull = function () { return this.getNumPhotos() > this.getMaxPhotos(); };
_p.isFull = function () { return this.getNumPhotos() == this.getMaxPhotos(); };
_p.sync = function () {
  var padding = (this.jdom.width()-this.getMaxPhotos()*this.slideWidth)/2;
  this.jdom.css('padding-left', padding);
  while (this.isOverFull()) {
    this.jdom.children(':first').remove();
  }
};
_p.add = function (photo) {
  /* Returns true if the stream is not full yet */
  if (this.isFull()) {
    this.jdom.children(':first').hide(this.fadeTime, function() { $(this).remove(); });
    this.append(photo);
    return false;
  } else {
    this.append(photo);
    return !this.isFull();
  }
};
_p.init = function () {
  var self = this;
  $.getJSON('/api/photos/recent/'+Math.round(this.getMaxPhotos() * 1.5),
    function (photos) {
      if (photos.length < 1) { return; }
      while (self.add(photos[0])) { photos.push(photos.shift()); }
      self.ready = true;
      (function() { /* Cycle through the photos */
        self.add(photos[0]);
        photos.push(photos.shift());
        setTimeout(arguments.callee, self.timeBetween);
      })();
    }
  );
};
return _;
})();

LGG.setupDetailedStreams = function () {
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
      mapTypeId: google.maps.MapTypeId.ROADMAP,
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

LGG.fade = function (jdom) {
  var left = $('<span class="fade left"></span>');
  var right = $('<span class="fade right"></span>');
  jdom.css('position', 'relative').append(left).append(right);
};

LGG.dimmedDialog = function(jdom) {
  var HTML_DIMMER = '<div class="dialog dimmer"></div>';
  var HTML_TAB_CLOSE = '<img class="dialog close" src="/img/button_close.png" />';
  var dimmer = $(HTML_DIMMER);
  var close = $(HTML_TAB_CLOSE)
    .mousedown(function () { $(this).fadeTo('fast', 0.8); });
  var destroy = function () {
    dimmer.hide('fast');
    dimmer.detach();
    close.detach();
    jdom.detach();
  }
  jdom.addClass('dialog win');
  jdom.click(function (e) { e.stopPropagation(); });
  dimmer.appendTo('body').append(jdom).show();
  close.appendTo(jdom).click(destroy);
};

LGG.dimmedDialogue = function(jdom) {
  var HTML_DIMMER = '<div class="dialog dimmer"></div>';
  var HTML_BORDER = '<div class="border"></div>';
  var HTML_CLOSE = '<img class="dialog close" src="/img/button_close.png" />';
  var dimmer = $(HTML_DIMMER);
  var border = $(HTML_BORDER).appendTo(dimmer);
  var close = $(HTML_CLOSE)
    .mousedown(function () { $(this).fadeTo('fast', 0.8); });
  var destroy = function () {
    dimmer.hide('fast');
    dimmer.detach();
    close.detach();
    jdom.detach();
  }
  jdom.addClass('dialog win')
      .click(function (e) { e.stopPropagation(); })
      .appendTo(border);
  dimmer.appendTo('body').show();
  close.appendTo(jdom).click(destroy);
};

LGG.showWelcome = function () {
  var HTML_WELCOME = [
    '<div id="welcome">',
      '<div class="header">',
        '<img src="/img/logo/medium_no_tagline.png" />',
        "<h1><em>See</em> and <em>show</em> what's happening near you.</h1>",
      '</div>',
      '<div class="how">',
        "<p>It's easy. <em>Download the app.</em></p>",
        "<p>Sign in and sync with your social networks.</p>",
        "<p>See and show what's happening near you.",
      '</div>',
      '<table class="steps">',
        '<tr>',
          '<td class="app">',
            '<h1><div class="numcircle">1</div></h1>',
            '<h2>Download the app.</h2>',
            '<table>',
              '<tr><td><img src="/img/welcome/logo_apple.png" /></td><td><img src="/img/welcome/logo_android.png" /></td></tr>',
              '<tr><td><img src="/img/welcome/app_iphone.png" /></td><td><img src="/img/welcome/app_android.png" /></td></tr>',
            '</table>',
          '</td>',
          '<td class="connect">',
            '<h1><div class="numcircle">2</div></h1>',
            '<h2>Sign in and sync with your social networks.</h2>',
            '<img class="clickable" src="/img/welcome/janrain.png" />',
          '</td>',
          '<td class="photos">',
            '<h1><div class="numcircle">3</div></h1>',
            "<h2>See and show what's happening near you!</h2>",
            '<img src="/img/welcome/taking_photo.png" />',
          '</td>',
      '</table>',
      '<div class="bigapp"><img src="/img/welcome/app_big_iphone.png" /></div>',
    '</div>'].join('');
  var welcome = $(HTML_WELCOME);
  LGG.dimmedDialog(welcome);
  $('.connect img', welcome).click(function () {
     if ($('.sign.in')) {
       $('.sign.in a').click();
     } else {
       window.open("https://livegather.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2F" + window.location.host + "%2Frpx.php");
     }
     return false;
  });
};

LGG.showSignup = function () {
  var HTML_SIGNUP = [
    '<div id="signup">',
      '<div class="header">',
        "<h1>You're almost done...</h1>",
        '<img src="/img/logo/small_no_tagline.png" />',
      '</div>',
      '<div>',
        '<form>',
          '<table>',
            '<tr><th>Email</th><td><input type="text" name="email" class="input" /></td></tr>',
            '<tr><th>Username</th><td><input type="text" name="username" class="input" /></td></tr>',
            '<tr><th></th><td class="username_sample"></td>',
            '<tr><th>Display Name</th><td><input type="text" name="display" class="input" /></td></tr>',
            '<tr><td colspan="2">',
            '<p>By clicking &ldquo;Finished&rdquo; you agree to our <a href="/privacy">Privacy Policy</a> and <a href="/tos">Terms of Service</a>.</p></td></tr>',
            '<tr><td colspan="2" class="finished">',
              '<input type="submit" value="Finished" />',
            '</td></tr>',
          '</table>',
        '</form>',
      '</div>',
    '</div>'].join('');
  var signup = $(HTML_SIGNUP);
  var username = $('input[name=username]', signup);
  var sample = $('.username_sample', signup);
  function updateSample() {
    // TODO Check username
    if (true) {
      sample.html('<p class="ok">Your profile will be livegather.com/<span>' + username.val() + '</span></p>');
    } else {
      sample.html('<p class="error">This username has been taken. Please try another.</p>');
    }
  }
  updateSample();
  username.keyup(updateSample);
  var submit = $('input[type=submit]', signup);
  submit.click(function () {
    if (valid) {
      // Do submit
    } else {
    }
    return false;
  });
  LGG.dimmedDialogue(signup);
};

LGG.showPhoto = function(id) {
  var viewer = $([
    '<div class="viewphoto"></div>'].join(''));
  LGG.dimmedDialog(viewer);

  $.getJSON('/api/photos/'+id, function(p) {
    var display = [
      '<div class="header">',
      '<a href="#"><img src="/api/users/', p.user_id, '/photo" /></a>',
      '<h1>Tony Mandarano</h1>',
      '<h2>', p.caption, '</h2>',
      '<ul class="tags">',
        '<li>tag1 is four words</li>',
        '<li>tag2</li>',
        '<li>tag3</li>',
      '</ul>',
      '<h3>', LG.dateToVernacular(p.date_added), '</h3>',
      '</div>',
      '<table class="split">',
        '<tr>',
          '<td class="photo">',
            '<img class="sround" src="/api/photo/'+id+'" />',
          '</td>',
          '<td>',
            '<div class="gathered sround">',
              '12 people recently gathered nearby',
            '</div>',
            '<h1 class="bichrome"><em>Similar</em> photos nearby.</h1>',
            '<ul class="collage similar photos"></ul>',
            '<div class="viewmap"></div>',
          '</td>',
        '</tr>',
      '</table>'
    ].join('');
    viewer.append($(display));

    var similar_photos = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    LG.G.html.collage.photos($('.similar.photos', viewer), similar_photos);

    p.latitude = 32.7; p.longitude = -117.1;
    var mapOpts = {
      zoom: 15,
      center: new GM.LatLng(defaultTo(p.latitude, 0), defaultTo(p.longitude, 0)),
      mapTypeId: GM.MapTypeId.ROADMAP,
      disableDefaultUI: true,
    };
    var map = new GM.Map($(".viewmap", viewer)[0], mapOpts);
    if (p.latitude && p.longitude) {
      new GM.Marker({position: map.getCenter(), map: map});
    }
  });
};

LGG.showSigninPrompt = function(jdom) {
  var HTML_SIGNINUP = [
    '<div class="signinup">',
    '<ul><li><a href="#tab1"><span>Sign up</span></a></li>',
    '<li><a href="#tab2"><span>Sign in</span></a></li>',
    '</ul>',
    '</div>'].join('');
  var HTML_TAB_ONE = ['<div id="tab1">',
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
  var HTML_TAB_TWO = [
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

LGG.setupAccountMenu = function () {
  var ol = $('.nav .account ol').hide();
  $('.nav .account').click(function () {
    if (ol.is(':visible')) {
      ol.hide();
    } else {
      ol.show();
    }
  });
};

LGG.setupDefaultingInputFields = function (def) {
  /* Input fields with default values will automatically clear and restore the
   * default value when no user input is supplied. */
  $('.default:text')
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

LGG.init = function () {
  if (window.location.hash == '#welcome') {
    // If the url has the welcome anchor show the welcome dialog.
    LGG.showWelcome();
  } else if (window.location.hash == '#signup') {
    // If the url has the signup anchor show the signup dialog.
    LGG.showSignup();
  }

  LGG.setupDefaultingInputFields('default');
  LGG.setupAccountMenu();

  ///* JSify sign in */
  //$('.sign.in img').click(function () {
  //  LGG.showSigninPrompt($(this));
  //  return false;
  //});
  

  //new LGG.HeaderStream($('#headerstream'));
  //LGG.setupDetailedStreams();
};
return LGG;
})();

$(LG.G.init);
