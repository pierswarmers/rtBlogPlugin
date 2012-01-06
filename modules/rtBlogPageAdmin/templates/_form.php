<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_javascript('/rtCorePlugin/vendor/jquery/js/jquery.min.js') ?>
<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate', 'rtAdmin') ?>

<?php slot('rt-tools') ?>
<?php include_partial('rtAdmin/standard_modal_tools', array('show_route_handle' => 'admin', 'object' => $form->getObject()))?>
<?php end_slot(); ?>

<?php slot('rt-side') ?>
<?php include_component('rtAsset', 'form', array('object' => $form->getObject())) ?>
<?php end_slot(); ?>

<?php include_partial('rtAdmin/flashes') ?>

<form id ="rtAdminForm" action="<?php echo url_for('rtBlogPageAdmin/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php echo $form->renderHiddenFields(false) ?>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
<input type="hidden" name="rt_post_save_action" value="edit" />
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <?php echo render_form_row($form['title']); ?>
      <?php echo render_form_row($form['content']); ?>
    </tbody>
  </table>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Publish Status') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <?php echo render_form_row($form['published']); ?>
        <?php echo render_form_row($form['published_from']); ?>
        <?php echo render_form_row($form['published_to']); ?>
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Metadata and SEO') ?></h2>
    <table class="rt-admin-toggle-panel-content" id="rtSortableCategories">
      <tbody>      
        <?php echo render_form_row($form['description']); ?>
        <?php echo render_form_row($form['tags']); ?>
        <?php if(isset ($form['rt_categories_list'])): ?>
          <?php echo render_form_row($form['rt_categories_list']); ?>        
        <?php endif; ?>
      </tbody>
    </table>
    <script type="text/javascript">
    $(function() {
      $("#rtSortableCategories ul.checkbox_list").first().sortable({
        revert: true
      });
    });
    </script>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Comments') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <?php echo render_form_row($form['comment_status']); ?>
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Author Details') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <?php echo render_form_row($form['author_name']); ?>
        <?php echo render_form_row($form['author_email']); ?>        
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Location and Referencing') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <?php echo render_form_row($form['slug']); ?>
      <?php if(isset($form['site_id'])): ?>
        <?php echo render_form_row($form['site_id']); ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</form>
