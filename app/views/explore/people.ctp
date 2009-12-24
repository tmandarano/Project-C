<h1>Explore People</h1>

<div class="location selector">World</div>

<table class="recent">
  <tr>
    <td>
      <h1>Recently near you</h1>
      <ul class="users centered">
        <li><img src="/users/photo/11" /> <span class="username">Jim Johnson</span> <span class="time">14 seconds ago</span></li>
        <li><img src="/users/photo/11" /> <span class="username">Mat Smith</span> <span class="time">36 seconds ago</span></li>
        <li><img src="/users/photo/11" /> <span class="username">Becca John</span> <span class="time">55 seconds ago</span></li>
      </ul>
    </td>
    <td>
      <div id="recently_near_people_map" class="map"></div>
    </td>
    <td>
      <h1>Popular people</h1>
      <ul class="users centered">
        <li><img src="/users/photo/11" /> <span class="username">Tony Mandarano</span> <span class="popularity">+253 watchers</span></li>
        <li><img src="/users/photo/11" /> <span class="username">John Stone</span> <span class="popularity">+133 watchers</span></li>
        <li><img src="/users/photo/11" /> <span class="username">Jimmy John</span> <span class="popularity">+121 watchers</span></li>
      </ul>
    </td>
  </tr>
</table>

<div class="search people">
<h1>Search People</h1>
<div class="searchbox">
<form name="people" class="search">
<label for="interests">Discover people interested in</label>
<input type="text" name="interests" /><input type="image" src="/img/search.png" name="search" />
</form>
</div>

<ul class="users">
  <li>
    <img src="/users/photo/11" />
    <span class="username">Tony Mandarano</span>
    <ul>
      <li><strong class="label">Location</strong> <span class="location">Seattle, WA</span></li>
      <li><strong class="label">Bio</strong> A true <span class="match">audi</span> s4 enthusiast! Oh, and I love Seattle!</li>
      <li><strong class="label">Interests</strong> <span class="match">audi</span>, <span class="match">cars</span>, hiking, Seahawks, Seattle Sounders, Formula One.</li>
    </ul>
    <div class="photo"><img src="/photos/4b12d65c-f9dc-428d-ab64-7d3c2641192c/3" /></div>
  </li>
  <li>
    <img src="/users/photo/11" />
    <span class="username">John Matthews</span>
    <ul>
      <li><strong class="label">Location</strong> <span class="location">Seattle, WA</span></li>
      <li><strong class="label">Bio</strong> I am a <span class="match">car</span> blogger and columnist, say hi!</li>
      <li><strong class="label">Interests</strong> <span class="match">cars</span>, lamborghini, <span class="match">audi</span>, autoclubs, autocross events, kayaking, waterskiing.</li>
    </ul>
    <div class="photo"><img src="/photos/4b12d65c-f9dc-428d-ab64-7d3c2641192c/3" /></div>
  </li>
</ul>
</div>
<?php $javascript->link('explore_people', false); ?>
