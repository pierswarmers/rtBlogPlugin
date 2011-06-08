<?php

/** @var rtBlogPage $rt_blog_page */

use_helper('I18N', 'Date', 'rtText')

?>

<div class="section rt-site-page">

  <div class="section-tools-header">
    <?php echo link_to(__('Edit'), 'rtBlogPageAdmin/edit?id='.$rt_blog_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
  </div>

  <?php if(sfConfig::get('app_rt_templates_headers_embedded', true)): ?>
  <div class="section-header">
    <h1><?php echo $rt_blog_page->getTitle() ?></h1>
    <div class="metas">
      <?php echo __('By') . ' ' . $rt_blog_page->getAuthorName() ?>
      <?php echo __('on') . ' ' . format_date($rt_blog_page->getPublishedFrom(), 'D', $sf_user->getCulture()) ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="section-content">
    <?php echo markdown_to_html($rt_blog_page->getContent(), $rt_blog_page); ?>
  </div>

</div>