<li>
  <a href="/profile/{$user.id}"><img src="/users/photo/{$photo.id}" /></a>
  <a href="/profile/{$user.id}" class="username">{$user.name}</a>
  <ul>
    <li><span class="label">Location</span> <span class="location">{$user.location}</span></li>
    <li><span class="label">Bio</span> {$user.bio}</li>
    <li><span class="label">Interests</span> {$user.interests}</li>
  </ul>
  <div class="photo"><a href="/api/photos/{$photo.id}"><img src="/photos/{$photo.id}/3" /></a></div>
</li>
