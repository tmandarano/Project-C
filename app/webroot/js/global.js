var LG = LG ? LG: {};
LG.G = {};
LG.G.HeaderStream = function(jdom) {
  this.jdom = jdom;
  this.timeBetween = 1000;
  this.slideWidth = 50;
  this.slideHeight = 50;
  this.slideCount = this.jdom.width() / this.slideWidth-1;
  this.slideOffset = (this.jdom.width() - this.slideCount * this.slideWidth) / 2;
};
LG.G.HeaderStream.prototype = function() {};
LG.G.HeaderStream.prototype.append = function(photo) {
  var pic = photo.Photo;
  var usr = photo.User;
  this.jdom.children(':last').fadeTo(600, 0.5);
  $('<li><a href="/photos/view/'+pic.id+'"><img src="/photos/'+pic.id+'/1" /></a></li>')
    .css({'height': 50, 'overflow-y': 'hidden', 'display': 'table-cell', 'vertical-align': 'middle'})
    .fadeIn(600)
    .hover(function() {
      $(this).fadeTo(200, 1.0);
    }, function() {
      $(this).fadeTo(200, 0.5);
    })
    .appendTo(this.jdom);
};
LG.G.HeaderStream.prototype.add = function(photo) {
  var self = this;
  if (self.jdom.children().length >= this.slideCount) { 
    self.jdom.children(':first').hide(300, function() {
      $(this).remove();
      self.append(photo);
    });
  } else {
    self.append(photo);
  }
};

$(document).ready(function() {
  var headerStream = new LG.G.HeaderStream($('#headerstream'));
  /* TODO make request to server for x most recent photos. Probably should ask for 1.5x the number necessary to make it look better */
  var photos = [{"Photo":{"id":"4b149b72-8780-47a2-8b73-663345a3cb7d","user_id":"13","caption":"trying out the new webcam","datetime":"2 weeks, 3 days ago","location":["-109.85675803516","32.121545824799"]},"User":{"email":"tony","location":["",null],"interests":"","id":"13","name":"tmandarano","photo_id":null},"Comment":[]},{"Photo":{"id":"4b12e638-f028-48b9-af33-0a572641192c","user_id":"11","caption":"awesome cars","datetime":"2 weeks, 4 days ago","location":["-114.4572829375","33.616334838414"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d768-4c24-4d29-bff2-7cac2641192c","user_id":"11","caption":"i'm all over the world!","datetime":"2 weeks, 4 days ago","location":["-122.51026145312","37.807071565225"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c","user_id":"11","caption":"football in the gulf!","datetime":"2 weeks, 4 days ago","location":["-88.738288796875","25.037705030077"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d1dd-e77c-4da9-b794-7c2f2641192c","user_id":"11","caption":"FIRE!","datetime":"2 weeks, 4 days ago","location":["-118.52222434375","34.381412744996"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b12d1cb-062c-4d22-aa0e-7ba22641192c","user_id":"11","caption":"FIRE!","datetime":"2 weeks, 4 days ago","location":["-118.52222434375","34.381412744996"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b121c60-8f54-4d69-b4be-69e62641192c","user_id":"11","caption":"more beach weather!","datetime":"2 weeks, 5 days ago","location":["-117.137947","32.77977"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b11f698-68f8-409a-940c-3ec145a3cb7d","user_id":"13","caption":"beach time","datetime":"2 weeks, 5 days ago","location":["-181","-91"]},"User":{"email":"tony","location":["",null],"interests":"","id":"13","name":"tmandarano","photo_id":null},"Comment":[]},{"Photo":{"id":"4b11f662-a54c-4d45-9dae-3ec145a3cb7d","user_id":"13","caption":"At the beach, beautiful day","datetime":"2 weeks, 5 days ago","location":["-181","-91"]},"User":{"email":"tony","location":["",null],"interests":"","id":"13","name":"tmandarano","photo_id":null},"Comment":[]},{"Photo":{"id":"4b11d228-06fc-4bd9-be47-55342641192c","user_id":"11","caption":"I like computers!","datetime":"2 weeks, 5 days ago","location":["-181","-91"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b0ed330-4214-4677-92f7-5f702641192c","user_id":"14","caption":"MORE FIRE?!","datetime":"3 weeks ago","location":["-181","-91"]},"User":{"email":"test@test.com","location":["",null],"interests":"","id":"14","name":"Testuser","photo_id":null},"Comment":[]},{"Photo":{"id":"4b0ed024-3ac4-4b80-a87d-235f2641192c","user_id":"11","caption":"Fires in LA!","datetime":"3 weeks ago","location":["-181","-91"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]},{"Photo":{"id":"4b0e63ea-4e64-4918-b088-2b942641192c","user_id":"11","caption":"computer's are cool!","datetime":"3 weeks ago","location":["-181","-91"]},"User":{"email":"asdf","location":["",null],"interests":"interests;are;semi;separated","id":"11","name":"Etaoin Shrdlu","photo_id":"4b12d65c-f9dc-428d-ab64-7d3c2641192c"},"Comment":[]}];

  /* Cycle through the photos */
  (function() {
    if (photos.length < 1) {return;}
    photos.push(photos.shift());
    headerStream.add(photos[0]);
    setTimeout(arguments.callee, headerStream.timeBetween);
  })();
});
