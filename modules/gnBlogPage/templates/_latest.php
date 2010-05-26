<?php if($gn_blog_posts): ?>
<ul>
<?php foreach($gn_blog_posts as $gn_blog_post): ?>
  <li><?php echo link_to($gn_blog_post->getTitle(), 'gn_blog_page_show', $gn_blog_post) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>