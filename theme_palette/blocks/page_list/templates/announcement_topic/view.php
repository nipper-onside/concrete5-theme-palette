<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Core::make('helper/text');
$c = Page::getCurrentPage();
$dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
$user = UserInfo::getByID($c->getCollectionUserID());
?>

<?php
      $parentID = $controller->cParentID;
      $page = Page::getbyID($parentID);
      $parentName = $page->getCollectionName();
?>

<?php if ( $c->isEditMode() && $controller->isBlockEmpty()) { ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php } else { ?>


					<section class="announcement topics">
						<ul class="announcement-list">
<?php  if (count($pages) > 0): ?>
    <?php

    $includeEntryText = false;
    if (
        (isset($includeName) && $includeName)
        ||
        (isset($includeDescription) && $includeDescription)
        ||
        (isset($useButtonForLink) && $useButtonForLink)
    ) {
        $includeEntryText = true;
    }

    foreach ($pages as $page):

		// Prepare data for each page being listed...
        $buttonClasses = 'ccm-block-page-list-read-more';
        $entryClasses = 'ccm-block-page-list-page-entry';
		$title = $th->entities($page->getCollectionName());
		$url = $nh->getLinkToCollection($page);
		$target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
		$target = empty($target) ? '_self' : $target;
		$description = $page->getCollectionDescription();
		$description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
		$description = $th->entities($description);
        $thumbnail = false;
        if ($displayThumbnail) {
            $thumbnail = $page->getAttribute('thumbnail');
        }
        $includeEntryText = false;
        if ($includeName || $includeDescription || $useButtonForLink) {
            $includeEntryText = true;
        }
        if (is_object($thumbnail) && $includeEntryText) {
            $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
        }

        //$date = $dh->formatDateTime($page->getCollectionDatePublic(), true);
		$date = $dh->date( t('F d, Y'),strtotime($page->getCollectionDatePublic()));
		$datetime = $dh->date('Y-m-d\TH:i:s', strtotime($page->getCollectionDatePublic()));
		$pubTime = strtotime($page->getCollectionDatePublic());
		$new = ((time() - $pubTime) < (60 * 60 * 24 * 7)) ? '<span class="new">New</span>' : '';

		//Other useful page data...


		//$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

		//$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

		/* CUSTOM ATTRIBUTE EXAMPLES:
		 * $example_value = $page->getAttribute('example_attribute_handle');
		 *
		 * HOW TO USE IMAGE ATTRIBUTES:
		 * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
		 * 2) Put in some code here like the following 2 lines:
		 *      $img = $page->getAttribute('example_image_attribute_handle');
		 *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
		 *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
		 *    (Change the last argument from false to true if you want thumbnails cropped.)
		 * 3) Output the image tag below like this:
		 *		<img src="<?php echo $thumb->src ?>" width="<?php echo $thumb->width ?>" height="<?php echo $thumb->height ?>" alt="" />
		 *
		 * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
		 * 1) Put in some code here like the following 2 lines:
		 * 	    $img_src = $img->getRelativePath();
		 *      $img_width = $img->getAttribute('width');
		 *      $img_height = $img->getAttribute('height');
		 * 2) Output the image tag below like this:
		 * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
		 */

		/* End data preparation. */

		/* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>

							<li>
					        <?php if ($includeEntryText): ?>
					            <div class="ccm-block-page-list-page-entry-text">

					                <?php if (isset($includeName) && $includeName): ?>
					                <div class="ccm-block-page-list-title">
					                    <?php if (isset($useButtonForLink) && $useButtonForLink) { ?>
					                        <h2><?php echo $title; ?><?php echo $new ?></h2>
					                    <?php } else { ?>
					                        <h2><a href="<?php echo $url ?>" target="<?php echo $target ?>"><?php echo $title ?></a><?php echo $new ?></h2>
					                    <?php } ?>
					                </div>
					                <?php endif; ?>

					                <?php if (isset($includeDate) && $includeDate): ?>
					                <div class="ccm-block-page-list-pub">
					                    <span class="page-date">
					                    	<time datetime="<?php echo $datetime; ?>"><?php echo $date; ?></time>
					                    </span>
					                    <span class="page-author">
											<?php echo $user->getUserDisplayName(); ?>
										</span>
									</div>
					                <?php endif; ?>

					                <?php if (isset($includeDescription) && $includeDescription): ?>
					                    <div class="ccm-block-page-list-description">
					                        <?php echo $description ?>
					                    </div>
					                <?php endif; ?>

					                <?php if (isset($useButtonForLink) && $useButtonForLink): ?>
					                <div class="ccm-block-page-list-page-entry-read-more">
					                    <a href="<?php echo $url?>" target="<?php echo $target?>" class="<?php echo $buttonClasses?>"><?php echo $buttonLinkText?></a>
					                </div>
					                <?php endif; ?>

					                </div>
					        <?php endif; ?>
							</li>

	<?php endforeach; ?>
<?php  else: ?>
							<li><p><?php echo t('No announcements to this news.')?></p></li>
<?php  endif; ?>
						</ul><!-- end .ccm-page-list -->

					</section>

    <?php if (count($pages) == 0): ?>
        <div class="ccm-block-page-list-no-pages"><?php echo h($noResultsMessage)?></div>
    <?php endif;?>


<?php if ($showPagination): ?>
    <?php echo $pagination;?>
<?php endif; ?>

<?php } ?>