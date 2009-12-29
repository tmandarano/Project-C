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
};
LG.G.HeaderStream.prototype = function() {};
LG.G.HeaderStream.prototype.append = function(json) {
  var p = json.Photo;
  $('<li><a href="/photos/view/'+p.id+'"><img src="/photos/'+p.id+'/1" title="'+json.User.name+': '+p.caption+'"/></a></li>')
    .data('json', json)
    .appendTo(this.jdom);
  this.jdom.trigger('change');
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
  /* TODO make request to server for x most recent photos. Probably should ask for 1.5x the number necessary to make it look better */
  var photos = [{"Photo":{"id":"4b149b72-8780-47a2-8b73-663345a3cb7d","user_id":"13","caption":"trying out the new webcam","datetime":"2 weeks, 3 days ago","location":["-109.85675803516","32.121545824799"]},"User":{"email":"tony","location":["",null],"interests":"","id":"13","name":"tmandarano","photo_id":null},"Comment":[]},{"Photo":{"id":"4b12e638-f028-48b9-af33-0a572641192c","user_id":"11","caption":"awesome cars","datetime":"2 weeks, 4 days ago","location":["-114.4572829375","33.616334838414"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d768-4c24-4d29-bff2-7cac2641192c","user_id":"11","caption":"i'm all over the world!","datetime":"2 weeks, 4 days ago","location":["-122.51026145312","37.807071565225"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c","user_id":"11","caption":"football in the gulf!","datetime":"2 weeks, 4 days ago","location":["-88.738288796875","25.037705030077"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d1dd-e77c-4da9-b794-7c2f2641192c","user_id":"11","caption":"FIRE!","datetime":"2 weeks, 4 days ago","location":["-118.52222434375","34.381412744996"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d1cb-062c-4d22-aa0e-7ba22641192c","user_id":"11","caption":"FIRE!","datetime":"2 weeks, 4 days ago","location":["-118.52222434375","34.381412744996"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b121c60-8f54-4d69-b4be-69e62641192c","user_id":"11","caption":"more beach weather!","datetime":"2 weeks, 5 days ago","location":["-117.137947","32.77977"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b11f698-68f8-409a-940c-3ec145a3cb7d","user_id":"13","caption":"beach time","datetime":"2 weeks, 5 days ago","location":["-181","-91"]},"User":{"email":"tony","location":["",null],"interests":"","id":"13","name":"tmandarano","photo_id":null},"Comment":[]},{"Photo":{"id":"4b11f662-a54c-4d45-9dae-3ec145a3cb7d","user_id":"13","caption":"At the beach, beautiful day","datetime":"2 weeks, 5 days ago","location":["-181","-91"]},"User":{"email":"tony","location":["",null],"interests":"","id":"13","name":"tmandarano","photo_id":null},"Comment":[]},{"Photo":{"id":"4b11d228-06fc-4bd9-be47-55342641192c","user_id":"11","caption":"I like computers!","datetime":"2 weeks, 5 days ago","location":["-181","-91"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b0ed330-4214-4677-92f7-5f702641192c","user_id":"14","caption":"MORE FIRE?!","datetime":"3 weeks ago","location":["-181","-91"]},"User":{"email":"test@test.com","location":["",null],"interests":"","id":"14","name":"Testuser","photo_id":null},"Comment":[]},{"Photo":{"id":"4b0ed024-3ac4-4b80-a87d-235f2641192c","user_id":"11","caption":"Fires in LA!","datetime":"3 weeks ago","location":["-181","-91"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b0e63ea-4e64-4918-b088-2b942641192c","user_id":"11","caption":"computer's are cool!","datetime":"3 weeks ago","location":["-181","-91"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]}];

  if (photos.length < 1) {return;}
  LG.G.headerStream = new LG.G.HeaderStream($('#headerstream'));
  while (LG.G.headerStream.add(photos[0])) { photos.push(photos.shift()); }

  (function() { /* Cycle through the photos */
    LG.G.headerStream.add(photos[0]);
    photos.push(photos.shift());
    setTimeout(arguments.callee, LG.G.headerStream.timeBetween);
  })();
});
