<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtSite') ?>

<?php $heading_tag = sfConfig::has('app_rt_blog_title') ? 'h2' : 'h1' ?>
<div class="rt-blog-page-index">
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

  <?php if ($pager->haveToPaginate()): ?>
    <p class="rt-pagination">
      <span class="back">
        <a href="<?php echo url_for('@rt_blog_page_pagination?page=1') ?>" class="first"><?php echo __('First') ?></a>
        <a href="<?php echo url_for('@rt_blog_page_pagination?page='.$pager->getPreviousPage()) ?>" class="previous"><?php echo __('Previous') ?></a>
      </span>
      <span class="pages">
      <?php foreach ($pager->getLinks() as $page): ?>
        <?php if ($page == $pager->getPage()): ?>
          <?php echo $page ?>
        <?php else: ?>
          <a href="<?php echo url_for('@rt_blog_page_pagination?page='.$page) ?>"><?php echo $page ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
      </span>
      <span class="forward">
      <a href="<?php echo url_for('@rt_blog_page_pagination?page='.$pager->getNextPage()) ?>" class="next"><?php echo __('Next') ?></a>
      <a href="<?php echo url_for('@rt_blog_page_pagination?page='.$pager->getLastPage()) ?>" class="last"><?php echo __('Last') ?></a>
      </span>
    </p>
  <?php endif; ?>

  <p class="rt-pagination-desc">
    <strong><?php echo count($pager) ?></strong> <?php echo __('posts in this blog') ?>
    <?php if ($pager->haveToPaginate()): ?>
      - <?php echo __('viewing page') ?> <strong><?php echo $pager->getPage() ?> <?php echo __('of') ?> <?php echo $pager->getLastPage() ?></strong>
    <?php endif; ?>
  </p>
</div>
