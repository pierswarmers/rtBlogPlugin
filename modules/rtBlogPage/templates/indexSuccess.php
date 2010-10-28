<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtSite') ?>
<?php if(count($pager->getResults()) > 0): ?>
    <?php $i = 1; foreach ($pager->getResults() as $rt_blog_page): ?>
      <div class="rt-list-item rt-list-item-<?php echo $i; ?> rt-admin-edit-tools-panel">
        <?php echo link_to(__('Edit'), 'rtBlogPageAdmin/edit?id='.$rt_blog_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
        <h1>
          <span class="date"><?php echo format_date($rt_blog_page->getPublishedFrom(), 'D', $sf_user->getCulture()) ?></span>
          <?php echo link_to($rt_blog_page->getTitle(), 'rt_blog_page_show',$rt_blog_page) ?>
        </h1>
        <?php if(sfConfig::get('app_rt_blog_index_mode', 'full') === 'full'): ?>
          <div class="rt-container">
            <?php use_helper('rtSocialNetworking') ?>
            <?php echo markdown_to_html($rt_blog_page->getContent(), $rt_blog_page, true); ?>
            <p class="rt-list-item-read-more"><?php echo link_to(__('Read more') . '&rarr;', 'rt_blog_page_show',$rt_blog_page) ?></p>
          </div>
        <?php elseif(sfConfig::get('app_rt_blog_index_mode') === 'description'): ?>
          <p><?php echo $rt_blog_page->getDescription(); ?></p>
        <?php endif; ?>
      </div>
    <?php $i++; endforeach; ?>
<?php else: ?>
  <p class="notice"><?php echo __('No posts available yet, please visit again later.') ?></p>
<?php endif; ?>
<?php include_partial('rtAdmin/pagination_public', array('pager' => $pager)); ?>