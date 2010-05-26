<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtSite') ?>

<div class="rt-blog-page-show">
  <h1><?php echo $rt_blog_page->getTitle() ?></h1>
  <?php include_partial('blog_page', array('rt_blog_page' => $rt_blog_page, 'sf_cache_key' => $rt_blog_page->getId())) ?>
</div>
