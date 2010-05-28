<?php use_helper('rtSocialNetworking') ?>
<div class="rt-page-content clearfix">
  <?php echo markdown_to_html($rt_blog_page->getContent(), $rt_blog_page); ?>
  <dl class="rt-meta-data">
    <dd><?php echo get_social_networking_badge(array('url' => url_for('rt_blog_page_show',$rt_blog_page, true), 'title' => $rt_blog_page->getTitle())) ?></dd>
    <dt><?php echo __('Created') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($rt_blog_page->getCreatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Updated') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($rt_blog_page->getUpdatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Version') ?>:</dt>
    <dd><?php echo $rt_blog_page->version ?></dd>
  </dl>
</div>
