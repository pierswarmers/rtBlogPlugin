<?php

/** @var rtBlogPage $rt_blog_page */

use_helper('I18N', 'Date', 'rtText')

?>

<?php if(count($pager->getResults()) > 0): ?>

  <?php $i = 1; foreach ($pager->getResults() as $rt_blog_page): ?>

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
        <?php $content_method = sfConfig::get('app_rt_blog_index_content_method', 'getContent'); ?>
        <?php echo markdown_to_html($rt_blog_page->$content_method(), $rt_blog_page); ?>
      </div>

      <div class="section-tools-footer">
        <?php echo link_to(__('Read more') . '&rarr;', 'rt_blog_page_show', $rt_blog_page) ?>
      </div>

    </div>

  <?php $i++; endforeach; ?>

<?php else: ?>

  <p class="notice"><?php echo __('No posts available yet, please visit again later.') ?></p>

<?php endif; ?>

<?php include_partial('rtAdmin/pagination_public', array('pager' => $pager)); ?>