<?php use_helper('I18N', 'rtAdmin') ?>

<h1><?php echo __('Listing Posts') ?></h1>

<?php slot('rt-tools') ?>
<?php include_partial('rtAdmin/standard_modal_tools', array('object' => new rtBlogPage))?>
<?php end_slot(); ?>

<table>
  <thead>
    <tr>
      <th><?php echo __('Title') ?></th>
      <th><?php echo __('Published') ?></th>
      <th><?php echo __('Version') ?></th>
      <th><?php echo __('Created at') ?></th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rt_blog_pages as $rt_blog_page): ?>
    <tr>
      <td><a href="<?php echo url_for('rtBlogPageAdmin/edit?id='.$rt_blog_page->getId()) ?>"><?php echo $rt_blog_page->getTitle() ?></a></td>
      <td><?php echo rt_nice_boolean($rt_blog_page->getPublished()) ?></td>
      <td><?php echo link_to_if($rt_blog_page->version > 1, $rt_blog_page->version, 'rtBlogPageAdmin/versions?id='.$rt_blog_page->getId()) ?></td>
      <td><?php echo $rt_blog_page->getCreatedAt() ?></td>
      <td>
        <ul class="rt-admin-tools">
          <li><?php echo rt_button_show(url_for('rtBlogPageAdmin/show?id='.$rt_blog_page->getId())) ?></li>
          <li><?php echo rt_button_edit(url_for('rtBlogPageAdmin/edit?id='.$rt_blog_page->getId())) ?></li>
          <li><?php echo rt_button_delete(url_for('rtBlogPageAdmin/delete?id='.$rt_blog_page->getId())) ?></li>
        </ul>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
