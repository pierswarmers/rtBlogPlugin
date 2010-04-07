<?php slot('gn-side') ?>
<?php if($sf_user->hasCredential(sfConfig::get('app_gn_blog_admin_credential', 'admin_blog'))): ?>
<p>
  <?php echo link_to(__('Create new post'), 'gnBlogPage/new', array('class' => 'button alternate')) ?>
  <?php if(isset($gn_blog_page)): ?>
  <?php echo link_to(__('Edit this post'), 'gnBlogPage/edit?id='.$gn_blog_page->getId(), array('class' => 'button positive')) ?>
  <?php endif; ?>
</p
<?php endif; ?>
<?php include_partial('gnSearch/form', array('form' => new gnSearchForm())) ?>
<?php end_slot(); ?>
