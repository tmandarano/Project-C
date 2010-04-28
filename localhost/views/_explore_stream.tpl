<div class="explore stream">
  <h1>{$h} <div class="tools">{include file='_timelocup.tpl'}{include file='_emotion.tpl'}</div></h1>
  <ol>
    {* Displays photos in a list. Please give at most 8. *}
    {foreach from=$photos key=id item=photo}
      <li><a href="#" onclick="viewpic('{$photo.id}?>')">
        <img src="/photos/{$photo.id}/1" /></a>
      </li>
    {/foreach}
  </ol>
</div>
