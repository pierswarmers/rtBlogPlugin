<?php if($rt_blog_posts): ?>
<ul>
<?php foreach($rt_blog_posts as $rt_blog_post): ?>
  <li><?php echo link_to($rt_blog_post->getTitle(), 'rt_blog_page_show', $rt_blog_post) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>