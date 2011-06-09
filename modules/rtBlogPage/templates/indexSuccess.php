<?php

/** @var rtBlogPage $rt_blog_page */

use_helper('I18N', 'Date', 'rtText')

?>

<?php slot('rt-title') ?>

  <div class="rt-section-header">
    <h1><?php echo __('Latest News') ?></h1>
  </div>

<?php end_slot(); ?>

<?php if(count($pager->getResults()) > 0): ?>

  <?php $i = 1; foreach ($pager->getResults() as $rt_blog_page): ?>

    <div class="rt-section rt-blog-page">

      <div class="rt-section-tools-header rt-admin-tools">
        <?php echo link_to(__('Edit Post'), 'rtBlogPageAdmin/edit?id='.$rt_blog_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
      </div>

      <div class="rt-section-header">
        <h1><?php echo link_to($rt_blog_page->getTitle(), 'rt_blog_page_show', $rt_blog_page) ?></h1>
        <div class="rt-metas">
          <?php echo __('By') . ' ' . $rt_blog_page->getAuthorName() ?>
          <?php echo __('on') . ' ' . format_date($rt_blog_page->getPublishedFrom(), 'D', $sf_user->getCulture()) ?>
        </div>
      </div>

      <div class="rt-section-content">
        <?php $content_method = sfConfig::get('app_rt_blog_index_content_method', 'getContent'); ?>
        <?php echo markdown_to_html($rt_blog_page->$content_method(), $rt_blog_page); ?>
      </div>

      <div class="rt-section-tools-footer">
        <?php echo link_to(__('Read more') . '&rarr;', 'rt_blog_page_show', $rt_blog_page) ?>
      </div>

    </div>

  <?php $i++; endforeach; ?>

<?php else: ?>

  <p class="notice"><?php echo __('No posts available yet, please visit again later.') ?></p>

<?php endif; ?>

<?php include_partial('rtAdmin/pagination_public', array('pager' => $pager)); ?>