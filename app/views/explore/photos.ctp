<h1>Explore Photos</h1>

<table class="split">
<tr>
  <td class="left pane">
    <div class="stream">
      <h1>Local <div class="tools"><?php echo $this->element('timelocup')?><?php echo $this->element('emotion')?></div></h1>
      <table class="photos">
        <tr>
          <?php for ($i=0; $i<8; $i++) {?>
            <td><img src="/img/mini_pic.jpg" /></td>
          <?php }?>
        </tr>
      </table>
    </div>

    <div class="stream">
      <h1>World <div class="tools"><?php echo $this->element('timelocup')?><?php echo $this->element('emotion')?></div></h1>
      <table class="photos">
        <tr>
          <?php for ($i=0; $i<8; $i++) {?>
            <td><img src="/img/mini_pic.jpg" /></td>
          <?php }?>
        </tr>
      </table>
    </div>
  </td>
  <td class="right pane">
    <h1>Popular keywords</h1>
    Tags here
  </td>
</tr>
</table>

<div class="search photos">
<h1>Search Photos</h1>
<div class="searchbox">
<form name="photos" class="search">
<label for="photosof">See photos of</label>
<input type="text" name="photosof" /><input type="image" src="/img/search.png" name="search" />
</form>
</div>

  <ol class="detailed stream">

    <li class="comments">
      <div class="photo">
        <a href="/photos/view/4b12e638-f028-48b9-af33-0a572641192c"><img src="/photos/4b12e638-f028-48b9-af33-0a572641192c/3" /></a>
        <ul class="emotions">
          <li>I think this photo is</li>
          <li><img src="/img/emotions/newsworthy.png" /></li>
          <li><img src="/img/emotions/cute_l.png" /></li>
          <li><img src="/img/emotions/happy_l.png" /></li>
          <li><img src="/img/emotions/interesting_l.png" /></li>
        </ul>
      </div>
      <div class="state"></div>
      <div class="detail comments">
        <div class="users">
          <a href="/users/profile/11"><img src="/users/photo/11" /></a>
          <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
          <span class="time">33 seconds ago</span>
          <span class="location">San Diego, CA</span>
          <p class="caption">Just getting back from the game!</p>
        </div>
        <ul class="users">
          <li>
            <a href="/users/profile/12"><img src="/users/photo/12" /></a>
            <a href="/users/profile/12" class="username">John Hanson</a>
            <span class="time">10 seconds ago</span>
            <p>Looks like you had a great time! Can't wait to hear about it when you get back.</p>
          </li>
          <li>
            <a href="/users/profile/11"><img src="/users/photo/11" /></a>
            <form class="comments" name="comments" action="/photos/comment" method="post"><input type="text" class="default" value="add comment" /><input type="submit" value="add" /></form>
          </li>
        </li>
      </div>
      <div class="detail map">
        <div class="map"></div>
        <h1>Near <span class="location">Mercer Island, WA</span></h1>
        <h1 class="time">December 17, 2009 1:04pm</h1>
        <ul class="users centered">
          <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <span class="time">20 seconds ago</span></li>
          <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <span class="time">42 seconds ago</span></li>
          <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <span class="time">3 minutes ago</span></li>
        </ul>
      </div>
      <div class="detail meta">
        <div class="summary">
          <p>This photo is <img src="/img/emotions/newsworthy.png" title="Newsworthy" alt="Newsworthy" /></p>
          <h1>Tags</h1>
          <ul>
            <li>football</li>
            <li>playoffs</li>
            <li>giants</li>
            <li>beer</li>
            <li>seahawks</li>
            <li>kouchdown</li>
          </ul>
          <form class="tags" name="tag" action="/tags/add" method="post"><input type="text" size="5" class="default" value="add tag" /><input type="submit" value="add" /></form>
        </div>
        <ul class="users">
          <li>
            <a href="/users/profile/11"><img src="/users/photo/11" /></a>
            <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
            <span class="time">33 seconds ago</span>
            <p>Gave stars</p>
          </li>
          <li>
            <a href="/users/profile/11"><img src="/users/photo/11" /></a>
            <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
            <span class="time">33 seconds ago</span>
            <p>Voted the photo <img src="/img/emotions/newsworthy.png" /></p>
          </li>
          <li>
            <a href="/usrs/profile/11"><img src="/users/photo/11" /></a>
            <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
            <span class="time">33 seconds ago</span>
            <p>Voted the photo <img src="/img/emotions/funny_l.png" /></p>
          </li>
        </ul>
      </div>
    </li>

    <li class="comments">
      <div class="state"></div>
      <div class="photo"><a href="/photos/view/4b121c60-8f54-4d69-b4be-69e62641192c"><img src="/photos/4b121c60-8f54-4d69-b4be-69e62641192c/3" /></a></div>
      <div class="detail comments">
        <div class="caption">
          <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span> <span class="location">San Diego, CA</span>
          <p>Just getting back from the game!</p>
        </div>
        <ul class="comments">
          <li class="comment">
            <img src="/users/photo/12" /><a class="username" href="/users/profile/12">John Hanson</a> <span class="time">10 seconds ago</span>
            <p>Looks like you had a great time! Can't wait to hear about it when you get back.</p>
          </li>
          <li class="comment">
            <img src="/users/photo/11" /><form><input type="text" /><input type="submit" /></form>
          </li>
        </li>
      </div>
      <div class="detail map">
        <div class="map"></div>
        <h1>Near <span class="location">Mercer Island, WA</span></h1>
        <h1 class="time">December 17, 2009 1:04pm</h1>
        <ul class="users centered">
          <li><img src="/users/photo/11" /> <span class="time">20 seconds ago</span></li>
          <li><img src="/users/photo/11" /> <span class="time">42 seconds ago</span></li>
          <li><img src="/users/photo/11" /> <span class="time">3 minutes ago</span></li>
        </ul>
      </div>
      <div class="detail meta">
        <div class="summary">
          <h1>Newsworthy</h1>
          <img class="emotion" src="/img/emotions/l/newsworthy.png" />
          <ul class="emotions">
            <li><img src="/img/emotions/newsworthy.png" /></li>
            <li><img src="/img/emotions/cute_l.png" /></li>
            <li><img src="/img/emotions/happy_l.png" /></li>
            <li><img src="/img/emotions/interesting_l.png" /></li>
          </ul>
          <div class="stars three"></div>
        </div>
        <ul class="users">
          <li>
            <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span>
            <p>Gave stars</p>
          </li>
          <li>
            <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span>
            <p>Voted the photo <img src="/img/emotions/newsworthy.png" /></p>
          </li>
          <li>
            <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span>
            <p>Voted the photo <img src="/img/emotions/funny_l.png" /></p>
          </li>
        </ul>
      </div>
    </li>
    <li class="comments">
      <div class="state"></div>
      <div class="photo"><a href="/photos/view/4b12d1dd-e77c-4da9-b794-7c2f2641192c"><img src="/photos/4b12d1dd-e77c-4da9-b794-7c2f2641192c/3" /></a></div>
      <div class="detail comments">
        <div class="caption">
          <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span> <span class="location">San Diego, CA</span>
          <p>Just getting back from the game!</p>
        </div>
        <ul class="comments">
          <li class="comment">
            <img src="/users/photo/12" /><a class="username" href="/users/profile/12">John Hanson</a> <span class="time">10 seconds ago</span>
            <p>Looks like you had a great time! Can't wait to hear about it when you get back.</p>
          </li>
          <li class="comment">
            <img src="/users/photo/11" /><form><input type="text" /><input type="submit" /></form>
          </li>
        </li>
      </div>
      <div class="detail map">
        <div class="map"></div>
        <h1>Near <span class="location">Mercer Island, WA</span></h1>
        <h1 class="time">December 17, 2009 1:04pm</h1>
        <ul class="users centered">
          <li><img src="/users/photo/11" /> <span class="time">20 seconds ago</span></li>
          <li><img src="/users/photo/11" /> <span class="time">42 seconds ago</span></li>
          <li><img src="/users/photo/11" /> <span class="time">3 minutes ago</span></li>
        </ul>
      </div>
      <div class="detail meta">
        <div class="summary">
          <h1>Newsworthy</h1>
          <img class="emotion" src="/img/emotions/l/newsworthy.png" />
          <ul class="emotions">
            <li><img src="/img/emotions/newsworthy.png" /></li>
            <li><img src="/img/emotions/cute_l.png" /></li>
            <li><img src="/img/emotions/happy_l.png" /></li>
            <li><img src="/img/emotions/interesting_l.png" /></li>
          </ul>
          <div class="stars three"></div>
        </div>
        <ul class="users">
          <li>
            <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span>
            <p>Gave stars</p>
          </li>
          <li>
            <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span>
            <p>Voted the photo <img src="/img/emotions/newsworthy.png" /></p>
          </li>
          <li>
            <img src="/users/photo/11" /><a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">33 seconds ago</span>
            <p>Voted the photo <img src="/img/emotions/funny_l.png" /></p>
          </li>
        </ul>
      </div>
    </li>
  </ol>
</div>
