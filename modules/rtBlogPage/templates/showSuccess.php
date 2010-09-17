<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtSite') ?>

<div class="rt-blog-page rt-show rt-primary-container rt-admin-edit-tools-panel">
  <?php echo link_to(__('Edit'), 'rtBlogPageAdmin/edit?id='.$rt_blog_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
  <h1>
    <span class="date"><?php echo format_date($rt_blog_page->getPublishedFrom(), 'D', $sf_user->getCulture()) ?></span> 
    <?php echo $rt_blog_page->getTitle() ?>
  </h1>
  <?php include_partial('blog_page', array('rt_blog_page' => $rt_blog_page, 'sf_cache_key' => $rt_blog_page->getId())) ?>
</div>