<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtSite') ?>

<?php slot('rt-title') ?>
<span class="date"><?php echo format_date($rt_blog_page->getPublishedFrom(), 'D', $sf_user->getCulture()) ?></span>
<?php echo $rt_blog_page->getTitle() ?>
<?php end_slot(); ?>

<?php echo link_to(__('Edit'), 'rtBlogPageAdmin/edit?id='.$rt_blog_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>

<?php include_partial('blog_page', array('rt_blog_page' => $rt_blog_page, 'sf_cache_key' => $rt_blog_page->getId())) ?>

<?php if(in_array($rt_blog_page->getCommentStatus(), array('open', 'user'))): ?>
  <?php include_component('rtComment', 'list', array('model' => 'rtBlogPage', 'model_id' => $rt_blog_page->getId(), 'title' => $rt_blog_page->getTitle())) ?>
  <?php include_component('rtComment', 'form', array('model' => 'rtBlogPage', 'model_id' => $rt_blog_page->getId())) ?>
<?php endif; ?>