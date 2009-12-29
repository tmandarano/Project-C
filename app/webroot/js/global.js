var LG = LG ? LG: {};
LG.G = {};
LG.G.HeaderStream = function(jdom) {
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
LG.G.HeaderStream.prototype = function() {};
LG.G.HeaderStream.prototype.append = function(json) {
  var p = json.Photo;
  $('<li><a href="/photos/view/'+p.id+'"><img src="/photos/'+p.id+'/1" title="'+json.User.name+': '+p.caption+'"/></a></li>')
    .data('json', json)
    .appendTo(this.jdom);
  if (this.ready) {
    this.jdom.trigger('change');
  }
};
LG.G.HeaderStream.prototype.getMaxPhotos = function() { return Math.floor(this.jdom.width() / this.slideWidth); };
LG.G.HeaderStream.prototype.getNumPhotos = function() { return this.jdom.children().length; };
LG.G.HeaderStream.prototype.isOverFull = function() { return this.getNumPhotos() > this.getMaxPhotos(); };
LG.G.HeaderStream.prototype.isFull = function() { return this.getNumPhotos() == this.getMaxPhotos(); };
/* Call this after a window resize */
LG.G.HeaderStream.prototype.sync = function() {
  var padding = (this.jdom.width()-this.getMaxPhotos()*this.slideWidth)/2;
  this.jdom.css('padding-left', padding);
  while (this.isOverFull()) {
    this.jdom.children(':first').remove();
  }
};
/* returns true if the stream is not full yet */
LG.G.HeaderStream.prototype.add = function(photo) {
  console.log('adding', photo);
  if (this.isFull()) {
    var self = this;
    this.jdom.children(':first').hide(this.fadeTime, function() {
      $(this).remove();
    });
    self.append(photo);
    return false;
  } else {
    this.append(photo);
    return !this.isFull();
  }
};

$(document).ready(function() {
  LG.G.headerStream = new LG.G.HeaderStream($('#headerstream'));
  $.getJSON('/photos/recent/'+LG.G.headerStream.getMaxPhotos()*1.5, function(photos) {
    if (photos.length < 1) {return;}
    while (LG.G.headerStream.add(photos[0])) { photos.push(photos.shift()); }
    LG.G.headerStream.ready = true;

    (function() { /* Cycle through the photos */
      LG.G.headerStream.add(photos[0]);
      photos.push(photos.shift());
      setTimeout(arguments.callee, LG.G.headerStream.timeBetween);
    })();
  });
});
