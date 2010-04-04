<h1>Share by Uploading</h1>

<form action="/photos/create" method="post" enctype="multipart/form-data">
<table>
  <tr>
    <th><label for="photo_photo">Photo</label></th>
    <td><input id="photo_photo" name="photo[photo]" type="file" /></td>
  </tr>
  <tr>
    <th><label for="photo_caption">Caption</label></th>
    <td><input id="photo_caption" name="photo[caption]" type="text" /></td>
  </tr>
  <tr>
    <th><label for="photo_tags">Tags</label></th>
    <td><input id="photo_tags" name="photo[tags]" type="text" /></td>
  </tr>
  <tr>
    <th>Location</th>
    <td>
      <input id="lng" name="photo[lng]" type="hidden" />
      <input id="lat" name="photo[lat]" type="hidden" />
      <div id="map" style="height: 400px; width: 400px;"></div>
    </td>
    <td>Please click on the map where the photo was taken</td>
  </tr>
  <tr>
    <th></th>
    <td><span style="font-size: x-large;">
      <input id="photo_submit_share" name="commit" type="submit" value="Share" />
    </span></td>
  </tr>
</table>
{capture name=scripts}
<script type="text/javascript" src="/js/share_upload.js"></script>
<script type="text/javascript" src="/lib/ajax-upload/ajaxupload.js"></script>
{/capture}