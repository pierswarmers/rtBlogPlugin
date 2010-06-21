<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtSite') ?>

<?php $heading_tag = sfConfig::has('app_rt_blog_title') ? 'h2' : 'h1' ?>
<div >
  <?php if(sfConfig::has('app_rt_blog_title')): ?>
  <h1><?php echo __(sfConfig::get('app_rt_blog_title', 'Latest News')) ?></h1>
  <?php endif; ?>
  <?php if(count($pager->getResults()) > 0): ?>
  <div class="rt-blog-page-index-list">
    <?php foreach ($pager->getResults() as $rt_blog_page): ?>
    <div class="rt-blog-page-index-item rt-admin-edit-tools-panel">
      <?php echo link_to(__('Edit'), 'rtBlogPageAdmin/edit?id='.$rt_blog_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
      <<?php echo $heading_tag ?>><?php echo link_to($rt_blog_page->getTitle(), 'rt_blog_page_show',$rt_blog_page) ?></<?php echo $heading_tag ?>>
      <?php if(sfConfig::get('app_rt_blog_index_mode', 'full') === 'full'): ?>
      <?php include_partial('blog_page', array('rt_blog_page' => $rt_blog_page, 'sf_cache_key' => $rt_blog_page->getId())) ?>
      <?php elseif(sfConfig::get('app_rt_blog_index_mode') === 'description'): ?>
      <p><?php echo $rt_blog_page->getDescription(); ?></p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p class="notice"><?php echo __('No posts available yet, please visit again later.') ?></p>
  <?php endif; ?>
</div>

<?php include_partial('rtAdmin/pagination_public', array('pager' => $pager)); ?>
