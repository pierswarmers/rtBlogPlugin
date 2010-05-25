<div class="gn-page-content clearfix">
  <?php echo markdown_to_html($gn_blog_page->getContent(), $gn_blog_page); ?>
  <dl class="gn-meta-data">
    <dt><?php echo __('Created') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($gn_blog_page->getCreatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Updated') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($gn_blog_page->getUpdatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Version') ?>:</dt>
    <dd><?php echo $gn_blog_page->version ?></dd>
  </dl>
</div>
