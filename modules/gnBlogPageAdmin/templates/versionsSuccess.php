<?php use_helper('I18n', 'Date', 'gnAdmin') ?>
        
<h1><?php echo __('Listing Versions') ?></h1>

<?php slot('gn-side') ?>
<p>
  <button type="submit" class="button positive" onclick="$('#gnBlogPageForm').submit()"><?php echo __('Compare selection') ?></button>
  <?php echo button_to(__('Cancel'),'gnBlogPageAdmin/index', array('class' => 'button cancel')) ?>
</p>
<?php end_slot(); ?>

<form id="gnBlogPageForm" action="<?php echo url_for('gnBlogPageAdmin/compare?id='.$gn_blog_page->getId()) ?>">
  <table class="stretch">
    <thead>
      <tr>
        <th style="width:30px;">#</th>
        <th><?php echo __('Title') ?></th>
        <th><?php echo __('Date') ?></th>
        <th style="width:30px;">1</th>
        <th style="width:30px;">2</th>
        <th style="width:50px;">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($gn_blog_page_versions as $version): ?>
      <tr>
        <td><?php echo $version->version ?></td>
        <td><?php echo $version->title ?></td>
        <td><?php echo $version->updated_at ?></td>
        <td><input type="radio" name="version1" value="<?php echo $version->version ?>" /></td>
        <td><input type="radio" name="version2" value="<?php echo $version->version ?>" /></td>
        <td>
          <ul class="gn-admin-tools">
            <li><?php echo gn_ui_button('revert', 'gnBlogPageAdmin/Revert?id='.$gn_blog_page->getId().'&revert_to='.$version->version, 'arrowrefresh-1-e'); ?></li>
          </ul>
        </td>
      <?php endforeach; ?>
    </tbody>
  </table>
</form>
