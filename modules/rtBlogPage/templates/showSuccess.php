<?php

/** @var rtBlogPage $rt_blog_page */

use_helper('I18N','Date')

?>

<?php slot('rt-title') ?>

  <div class="section-header">
    <h1><?php echo $rt_blog_page->getTitle() ?></h1>
    <div class="metas">
      <?php echo __('By') . ' ' . $rt_blog_page->getAuthorName() ?>
      <?php echo __('on') . ' ' . format_date($rt_blog_page->getPublishedFrom(), 'D', $sf_user->getCulture()) ?>
    </div>
  </div>

<?php end_slot(); ?>

<?php include_partial('blog_page', array('rt_blog_page' => $rt_blog_page, 'sf_cache_key' => $rt_blog_page->getId())) ?>

<?php if(in_array($rt_blog_page->getCommentStatus(), array('open', 'user'))): ?>
  <?php include_component('rtComment', 'panel', array('model' => 'rtBlogPage', 'model_id' => $rt_blog_page->getId(), 'title' => $rt_blog_page->getTitle())) ?>
<?php endif; ?>