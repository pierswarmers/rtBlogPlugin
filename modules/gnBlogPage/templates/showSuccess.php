<?php include_partial('use'); include_partial('tools', array('gn_blog_page' => $gn_blog_page)); ?>
<div class="gn-blog-page-show">
  <h1><?php echo $gn_blog_page->getTitle() ?></h1>
  <?php include_partial('blog_page', array('gn_blog_page' => $gn_blog_page, 'sf_cache_key' => $gn_blog_page->getId())) ?>
  <?php include_partial('meta_data', array('gn_blog_page' => $gn_blog_page)); ?>
</div>