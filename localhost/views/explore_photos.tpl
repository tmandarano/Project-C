<table class="split"><tr>
<td class="left pane">
  <div class="controls">
    <div class="cities">
      <h1>Popular Cities</h1>
      <ul>
        {foreach from=$popCities item=city}
          <li><a href="{$city}">{$city}</a></li>
        {/foreach}
      </ul>
    </div>
    <div class="tags">
      <h1>Trending Tags</h1>
      <ul>
        {foreach from=$trending item=tag}
          <li><a href="{$tag}">{$tag}</a></li>
        {/foreach}
      </ul>
    </div>

    <div class="search">
      <label for="see">See</label> <input name="see" type="text" /> 
      <label for="near">Near</label> <input name="near" type="text" />
      <input name="explore" type="submit" value="explore" />
    </div>
  </div>
  <div class="most streams">
    <div id="most_recent" class="most recent">
      <h1>most recent <div class="controls"></div></h1>
      <ul class="exp stream">
      </ul>
    </div>
    <div id="most_viewed" class="most viewed">
      <h1>most viewed <div class="controls"></div></h1>
      <ul class="exp stream">
      </ul>
    </div>
    <div id="most_liked" class="most liked">
      <h1>most liked <div class="controls"></div></h1>
      <ul class="exp stream">
      </ul>
    </div>
  </div>
</td>
<td class="right pane">
  <h1 class="monochrome"><em>Explore</em>.</h1>
  <h1 class="bichrome"><em>Photos</em> you might like.</h1>
  <ul class="collage">
    {foreach from=$suggestedPhotos item=photo}
      <li><a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/1" /></a></li>
    {/foreach}
  </ul>

  <h1 class="bichrome"><em>People</em> you might like.</h1>
  <ul class="collage">
    {foreach from=$suggestedPeople item=person}
      <li><a href="/profile/{$person.id}"><img src="/users/photo/{$person.id}" /></a></li>
    {/foreach}
  </ul>
</td>
</tr></table>
{capture name=scripts}
<script type="text/javascript" src="/js/explore_photos.js"></script>
{/capture}
