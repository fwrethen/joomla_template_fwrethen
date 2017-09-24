<?php
defined('_JEXEC') or die('Restricted access');

$this->cv = new stdClass();
?>

<ul class="thumbnails">
  <?php foreach ($this->categories as $ck => $cv): ?>
    <li class="">
      <div class="thumbnail" style="width: 112px; height: 180px;">
        <a href="<?php echo $cv->link; ?>">
          <?php	echo JHtml::_( 'image', $cv->linkthumbnailpath, $cv->title, array( 'style' => 'width: 112px; height: 112px;')); ?>
        </a>
        <h5>
          <a href="<?php echo $cv->link; ?>">
            <?php echo PhocaGalleryText::wordDelete($cv->title_self, $this->tmpl['char_cat_length_name'], '...'); ?>
          </a>
          <?php if ($cv->numlinks > 0): ?>
            <span class="pg-csv-count">(<?php echo $cv->numlinks ?>)</span>
          <?php endif; ?>
        </h5>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
