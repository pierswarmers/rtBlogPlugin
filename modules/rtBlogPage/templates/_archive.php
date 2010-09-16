<?php $year_header = ''; ?>
<?php if($rt_blog_posts): ?>
<dl class="rt-blog-page-archive">
  <?php $i=0; foreach($rt_blog_posts as $rt_blog_post): ?>
    <?php $month_name = date("F", mktime(0, 0, 0, $rt_blog_post['month'], 1, $rt_blog_post['year'])); ?>
    <?php if($year_header != $rt_blog_post['year']): ?>
      <dt><?php echo $rt_blog_post['year'] ?></dt>
    <?php endif; ?>
    <dd><?php echo link_to($month_name, url_for('rt_blog_page_ym',array('year' => $rt_blog_post['year'],'month' => $rt_blog_post['month']))) ?> (<?php echo $rt_blog_post['count'] ?>)</dd>
    <?php $year_header = $rt_blog_post['year'] ?>
  <?php $i++; endforeach; ?>
</dl>
<?php endif; ?>