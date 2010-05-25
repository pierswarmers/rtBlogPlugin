<?php use_helper('I18N', 'Date', 'gnText', 'gnForm', 'gnDate', 'gnSite') ?>

<div class="gn-blog-page-show">
  <h1><?php echo $gn_blog_page->getTitle() ?></h1>
  <?php include_partial('blog_page', array('gn_blog_page' => $gn_blog_page, 'sf_cache_key' => $gn_blog_page->getId())) ?>
</div>
