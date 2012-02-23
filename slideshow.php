<div class="slider-wrapper theme-default">
  <?php $slides = $slideshow->slides; ?>
  <div class="ribbon"></div>
  <div class="nivoSlider">
    <?php foreach($slides as $slide):?>
    <a href="<?php echo $slide->url; ?>" rel="<?php echo $slide->name; ?>">
      <img src="<?php echo $slide->image_url; ?>" title="<?php echo $slide->text; ?>" alt="" data-transition="fade">
    </a>
    <?php endforeach; ?>
  </div>
</div>
