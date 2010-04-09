<?php use_helper('gnSocialNetworking') ?>
<dl class="gn-meta-data">
  <dd><?php echo add_this_badge(array('url' => url_for('gn_blog_page_show',$gn_blog_page, true), 'title' => $gn_blog_page->getTitle())) ?></dd>
  <dt><?php echo __('Created') ?>:</dt>
  <dd><?php echo time_ago_in_words_abbr($gn_blog_page->getCreatedAt(), $sf_user->getCulture()) ?></dd>
  <dt><?php echo __('Updated') ?>:</dt>
  <dd><?php echo time_ago_in_words_abbr($gn_blog_page->getUpdatedAt(), $sf_user->getCulture()) ?></dd>
  <dt><?php echo __('Version') ?>:</dt>
  <dd><?php echo link_to($gn_blog_page->getVersion(), 'gnBlogPage/versions?id='.$gn_blog_page->getId()) ?></dd>
</dl>