<?php use_helper('I18N', 'Date', 'gnText', 'gnForm', 'gnDate', 'gnSite') ?>

<?php $heading_tag = sfConfig::get('app_gn_blog_index_title', false) ? 'h2' : 'h1' ?>
<div class="gn-blog-page-index">
  <?php if(sfConfig::get('app_gn_blog_index_title', false)): ?>
  <h1><?php echo __(sfConfig::get('app_gn_blog_index_title', 'Latest News')) ?></h1>
  <?php endif; ?>
  <?php if(count($pager->getResults()) > 0): ?>
  <div class="gn-blog-page-index-list">
    <?php foreach ($pager->getResults() as $gn_blog_page): ?>
    <div class="gn-blog-page-index-item">
      <<?php echo $heading_tag ?>><?php echo link_to($gn_blog_page->getTitle(), 'gn_blog_page_show',$gn_blog_page) ?></<?php echo $heading_tag ?>>
      <?php if(sfConfig::get('app_gn_blog_index_mode', 'full') === 'full'): ?>
      <?php include_partial('blog_page', array('gn_blog_page' => $gn_blog_page, 'sf_cache_key' => $gn_blog_page->getId())) ?>
      <?php elseif(sfConfig::get('app_gn_blog_index_mode') === 'description'): ?>
      <p><?php echo $gn_blog_page->getDescription(); ?></p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p class="notice"><?php echo __('No posts available yet, please visit again later.') ?></p>
  <?php endif; ?>

  <?php if ($pager->haveToPaginate()): ?>
    <p class="gn-pagination">
      <span class="back">
        <a href="<?php echo url_for('@gn_blog_page_pagination?page=1') ?>" class="first"><?php echo __('First') ?></a>
        <a href="<?php echo url_for('@gn_blog_page_pagination?page='.$pager->getPreviousPage()) ?>" class="previous"><?php echo __('Previous') ?></a>
      </span>
      <span class="pages">
      <?php foreach ($pager->getLinks() as $page): ?>
        <?php if ($page == $pager->getPage()): ?>
          <?php echo $page ?>
        <?php else: ?>
          <a href="<?php echo url_for('@gn_blog_page_pagination?page='.$page) ?>"><?php echo $page ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
      </span>
      <span class="forward">
      <a href="<?php echo url_for('@gn_blog_page_pagination?page='.$pager->getNextPage()) ?>" class="next"><?php echo __('Next') ?></a>
      <a href="<?php echo url_for('@gn_blog_page_pagination?page='.$pager->getLastPage()) ?>" class="last"><?php echo __('Last') ?></a>
      </span>
    </p>
  <?php endif; ?>

  <p class="gn-pagination-desc">
    <strong><?php echo count($pager) ?></strong> <?php echo __('posts in this blog') ?>
    <?php if ($pager->haveToPaginate()): ?>
      - <?php echo __('viewing page') ?> <strong><?php echo $pager->getPage() ?> <?php echo __('of') ?> <?php echo $pager->getLastPage() ?></strong>
    <?php endif; ?>
  </p>
</div>
