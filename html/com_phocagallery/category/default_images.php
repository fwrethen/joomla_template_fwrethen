<?php defined('_JEXEC') or die('Restricted access');
$app	= JFactory::getApplication();
// - - - - - - - - - -
// Images
// - - - - - - - - - -
if (!empty($this->items)) {
?>

<ul class="thumbnails">

<?php
	foreach($this->items as $ck => $cv) {

		if ($this->checkRights == 1) {
			// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
			// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
			$rightDisplay	= 0;
			if (isset($cv->catid) && isset($cv->cataccessuserid) && isset($cv->cataccess)) {
				$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $cv->cataccessuserid, $cv->cataccess, $this->tmpl['user']->getAuthorisedViewLevels(), $this->tmpl['user']->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - - - - -
		} else {
			$rightDisplay = 1;
		}

		// Display back button to categories list
		if ($cv->item_type == 'categorieslist'){
			$rightDisplay = 1;
		}

		if ($rightDisplay == 1) {

			// BOX Start
			?>
			<li class="">
				<div class="thumbnail" style="width: 112px; height: 180px;">
					<a class="<?php echo $cv->button->methodname; ?>"
						title="<?php if ($cv->type == 2 && $cv->overlib == 0) htmlentities($cv->odesctitletag, ENT_QUOTES, 'UTF-8'); ?>"
						href="<?php echo $cv->link; ?>"
						<?php // add attribute lightbox if picture
							/*if ($cv->type == 2) echo ' rel="lightbox[gallery]"';*/
						?>
						>

			<?php
			// Correct size for external Image (Picasa) - subcategory
			$extImage = false;
			if (isset($cv->extid)) {
				$extImage = PhocaGalleryImage::isExtImage($cv->extid);
				if (isset($cv->extw) && isset($cv->exth)) {
					$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($cv->extw, $cv->exth, $this->tmpl['picasa_correct_width_m'], $this->tmpl['picasa_correct_height_m'], $this->tmpl['diff_thumb_height']);
				}
			}
			// Image Box (image, category, folder)
			if ($cv->type == 2 ) {
				// IMG Start
				if ($extImage) {
					//echo JHtml::_( 'image', $cv->extm, $cv->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-image'));

					echo JHtml::_( 'image', $cv->extm, $cv->altvalue, array('style' => 'width: 112px; height: 112px;'));

				} else {
					echo JHtml::_( 'image', $cv->linkthumbnailpath, $cv->oimgalt, array('style' => 'width: 112px; height: 112px;', 'class' => $cv->ooverlibclass ));
				}

				if ($cv->type == 2 && $cv->enable_cooliris == 1) {
					if ($extImage) {
						echo '<span class="mbf-item">#phocagallerypiclens '.$cv->catid.'-phocagallerypiclenscode-'.$cv->extid.'</span>';
					} else {
						echo '<span class="mbf-item">#phocagallerypiclens '.$cv->catid.'-phocagallerypiclenscode-'.$cv->filename.'</span>';
					}
				}
				// IMG End

			} else if ($cv->type == 1) {
				// Other than image
				// IMG Start
				if ($extImage && isset($cv->extm) && isset($correctImageRes['width']) && isset($correctImageRes['width'])) {

					echo JHtml::_( 'image', $cv->extm, '', array('style' => 'width: 112px; height: 112px;', 'class' => PhocaGalleryRenderFront::renderImageClass($cv->extm)));
				} else {
					echo JHtml::_( 'image', $cv->linkthumbnailpath, '', array('style' => 'width: 112px; height: 112px;', 'class' => PhocaGalleryRenderFront::renderImageClass($cv->linkthumbnailpath)) );
				}
				// IMG END

			} else {
				// Other than image
				// IMG Start
				if ($extImage && isset($cv->extm) && isset($correctImageRes['width']) && isset($correctImageRes['width'])) {
					echo JHtml::_( 'image', $cv->extm, '', array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
				} else {
					echo JHtml::_( 'image', $cv->linkthumbnailpath, '');
				}
				// IMG END
			} // if type 2 else type 0, 1 (image, category, folder)
			?></a>
			<?php



			// Subfolder Name
			if ($cv->type == 1) {
				if ($cv->display_name == 1 || $cv->display_name == 2) {
					echo '<div class="pg-cv-name pg-cv-folder">'.PhocaGalleryText::wordDelete($cv->title, $this->tmpl['char_cat_length_name'], '...').'</div>';
				}
			}

			// Image Name
			if ($cv->type == 2) {
				if ($cv->display_name == 1) {
					echo '<div class="pg-cv-name">'.PhocaGalleryText::wordDelete($cv->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
				if ($cv->display_name == 2) {
					echo '<div class="pg-cv-name">&nbsp;</div>';
				}
			}

			// Description in Box
			if ($this->tmpl['display_img_desc_box'] == 1  && $cv->description != '') {
				echo '<div class="pg-cv-descbox">'. strip_tags($cv->description).'</div>'. "\n";
			} else if ($this->tmpl['display_img_desc_box'] == 2  && $cv->description != '') {
				echo '<div class="pg-cv-descbox">' .(JHtml::_('content.prepare', $cv->description, 'com_phocagallery.image')).'</div>'. "\n";
			}
			echo '</div>'. "\n";
		}
	}
?>
				</div>
			</li>
 <?php
}
