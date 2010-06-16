<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_javascript('/rtCorePlugin/vendor/jquery/js/jquery.min.js') ?>
<?php use_javascript('/rtCorePlugin/vendor/jquery/js/jquery.tools.min.js', 'last'); ?>
<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate') ?>

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
      <tr>
        <th><?php echo $form['title']->renderLabel() ?></th>
        <td>
          <?php echo $form['title']->renderError() ?>
          <?php echo $form['title'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['content']->renderLabel() ?></th>
        <td>
          <?php echo $form['content']->renderError() ?>
          <?php echo $form['content'] ?>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Publish Status') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <tr>
          <th><?php echo $form['published']->renderLabel() ?></th>
          <td>
            <?php echo $form['published']->renderError() ?>
            <?php echo $form['published'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['published_from']->renderLabel() ?></th>
          <td>
            <?php echo $form['published_from']->renderError() ?>
            <?php echo $form['published_from'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['published_to']->renderLabel() ?></th>
          <td>
            <?php echo $form['published_to']->renderError() ?>
            <?php echo $form['published_to'] ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Metadata and SEO') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <tr>
          <th><?php echo $form['description']->renderLabel() ?></th>
          <td>
            <?php echo $form['description']->renderError() ?>
            <?php echo $form['description'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['tags']->renderLabel() ?></th>
          <td>
            <?php echo $form['tags']->renderError() ?>
            <?php echo $form['tags'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['searchable']->renderLabel() ?></th>
          <td>
            <?php echo $form['searchable']->renderError() ?>
            <?php echo $form['searchable'] ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Comments') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <tr>
          <th><?php echo $form['comment_status']->renderLabel() ?></th>
          <td>
            <?php echo $form['comment_status']->renderError() ?>
            <?php echo $form['comment_status'] ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Author Details') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <tr>
          <th><?php echo $form['author_name']->renderLabel() ?></th>
          <td>
            <?php echo $form['author_name']->renderError() ?>
            <?php echo $form['author_name'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['author_email']->renderLabel() ?></th>
          <td>
            <?php echo $form['author_email']->renderError() ?>
            <?php echo $form['author_email'] ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="rt-admin-toggle-panel">
    <h2><?php echo __('Location and Referencing') ?></h2>
    <table class="rt-admin-toggle-panel-content">
      <tbody>
        <tr>
          <th><?php echo $form['slug']->renderLabel() ?></th>
          <td>
            <?php echo $form['slug']->renderError() ?>
            <?php echo $form['slug'] ?>
          </td>
        </tr>
      <?php if(isset($form['site_id'])): ?>
        <tr>
          <th><?php echo $form['site_id']->renderLabel() ?></th>
          <td>
            <?php echo $form['site_id']->renderError() ?>
            <?php echo $form['site_id'] ?>
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</form>
