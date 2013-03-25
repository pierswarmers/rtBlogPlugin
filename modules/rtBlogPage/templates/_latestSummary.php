<?php use_helper('I18N', 'Date', 'rtText', 'rtTemplate'); ?>

<?php if($rt_blog_posts): ?>
  <?php foreach($rt_blog_posts as $rt_blog_post): ?>
    <h3><?php echo link_to($rt_blog_post->getTitle(), 'rt_blog_page_show',$rt_blog_post) ?></h3>
    <?php echo markdown_to_html($rt_blog_post->getContent(), $rt_blog_post, true); ?>
    <p class="rt-list-item-read-more"><em><?php echo link_to(__('Read more') . '&rarr;', 'rt_blog_page_show',$rt_blog_post) ?></em></p>
  <?php endforeach; ?>
<?php endif; ?>