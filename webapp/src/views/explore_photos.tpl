<h1>Explore Photos</h1>

<table class="split"><tr>
<td class="left pane">
  {include file='_explore_stream.tpl' h=Local photos=array()}
  {include file='_explore_stream.tpl' h=World photos=array()}
</td>
<td class="right pane">
  <h1>Popular keywords</h1>
  Tags here
</td>
</tr></table>

<div class="search photos">
  <h1>Search Photos</h1>
  <div class="searchbox">
  <form name="photos" class="search">
  <label for="photosof">See photos of</label>
  <input type="text" name="photosof" /><input type="image" src="/img/search.png" name="search" />
  </form>
  </div>
  <ol class="detailed stream">
    {php}$this->assign('photo', array('id'=>'451'));{/php}
    {include file='_detailed_stream.tpl'}
    {php}$this->assign('photo', array('id'=>'452'));{/php}
    {include file='_detailed_stream.tpl'}
    {php}$this->assign('photo', array('id'=>'451'));{/php}
    {include file='_detailed_stream.tpl'}
  </ol>
</div>
