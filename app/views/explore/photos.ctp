<h1>Explore Photos</h1>

<table class="split"><tr>
<td class="left pane">
  <?php echo $this->element('explore_stream', array('h'=>'Local', 'photos'=>array()))?>
  <?php echo $this->element('explore_stream', array('h'=>'World', 'photos'=>array()))?>
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
    <?php echo $this->element('detailed_stream', array('photo'=>array('Photo'=>array('id'=>'4b12d1cb-062c-4d22-aa0e-7ba22641192c'))))?>
    <?php echo $this->element('detailed_stream', array('photo'=>array('Photo'=>array('id'=>'4b11f662-a54c-4d45-9dae-3ec145a3cb7d'))))?>
    <?php echo $this->element('detailed_stream', array('photo'=>array('Photo'=>array('id'=>'4b149b72-8780-47a2-8b73-663345a3cb7d'))))?>
  </ol>
</div>
