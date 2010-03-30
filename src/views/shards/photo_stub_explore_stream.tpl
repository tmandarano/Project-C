<img src="/photos/{$photo.id}/3" />
<div class="details">
{$photo.datetime}
{$photo.location}
<ul class="tags">
{foreach from=$photo.tags item=tag}
  <li>{$tag.tag}</li>
{/foreach}
</ul>
</div>
