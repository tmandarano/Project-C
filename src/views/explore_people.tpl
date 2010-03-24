<h1>Explore People</h1>

<div class="location selector">World</div>

<table class="recent">
  <tr>
    <td>
      <h1>Recently near you</h1>
      <ul class="users centered">
        <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <a href="/users/profile/11" class="username">Jim Johnson</a> <span class="time">14 seconds ago</span></li>
        <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <a href="/users/profile/11" class="username">Mat Smith</a> <span class="time">36 seconds ago</span></li>
        <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <a href="/users/profile/11" class="username">Becca John</a> <span class="time">55 seconds ago</span></li>
      </ul>
    </td>
    <td>
      <div id="recently_near_people_map" class="map"></div>
    </td>
    <td>
      <h1>Popular people</h1>
      <ul class="users centered">
        <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <a href="/users/profile/11" class="username">Tony Mandarano</a> <span class="popularity">+253 watchers</span></li>
        <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <a href="/users/profile/11" class="username">John Stone</a> <span class="popularity">+133 watchers</span></li>
        <li><a href="/users/profile/11"><img src="/users/photo/11" /></a> <a href="/users/profile/11" class="username">Jimmy John</a> <span class="popularity">+121 watchers</span></li>
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
  {php}$this->assign('user', array(
      'id'=>11,'name'=>'Tony Mandarano', 
      'location' => 'Seattle, WA',
      'bio'=>'A true audi s4 enthusiast! Oh, and I love Seattle!',
      'interests'=>array('audi', 'cars', 'hiking', 'Seahawks',
                         'Seattle Sounders', 'Formula One')));
      $this->assign('photo', array('id'=>'451'));
  {/php}
  {include file='_user_summary.tpl' userinfo=$user photo=$photo}
  {php}$this->assign('user', array(
      'id'=>12,'name'=>'John Matthews', 
      'location' => 'Seattle, WA',
      'bio'=>'I am a car blogger and columnist, say hi!',
      'interests'=>array('cars', 'lamboghini', 'audi', 'autoclubs', 'autocross
      events', 'kayaking', 'waterskiing')));
    $this->assign('photo', array('id'=>'452'));
  {/php}
  {include file='_user_summary.tpl' userinfo=$user photo=$photo}
</ul>
</div>
<?php $javascript->link('explore_people', false); ?>
