<?php use_helper('I18n', 'gnAdmin') ?>

<h1><?php echo __('Listing Posts') ?></h1>

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
    <?php foreach ($gn_blog_pages as $gn_blog_page): ?>
    <tr>
      <td><a href="<?php echo url_for('gnBlogPageAdmin/edit?id='.$gn_blog_page->getId()) ?>"><?php echo $gn_blog_page->getTitle() ?></a></td>
      <td><?php echo gn_nice_boolean($gn_blog_page->getPublished()) ?></td>
      <td><?php echo link_to_if($gn_blog_page->version > 1, $gn_blog_page->version, 'gnBlogPageAdmin/versions?id='.$gn_blog_page->getId()) ?></td>
      <td><?php echo $gn_blog_page->getCreatedAt() ?></td>
      <td>
        <ul class="gn-admin-tools">
          <li><?php echo gn_button_show(url_for('gn_blog_page_show', $gn_blog_page)) ?></li>
          <li><?php echo gn_button_edit(url_for('gnBlogPageAdmin/edit?id='.$gn_blog_page->getId())) ?></li>
          <li><?php echo gn_button_delete(url_for('gnBlogPageAdmin/delete?id='.$gn_blog_page->getId())) ?></li>
        </ul>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php slot('gn-side') ?>
<p><?php echo button_to(__('Create new post'), 'gnBlogPageAdmin/new', array('class' => 'button positive')) ?></p>
<?php end_slot(); ?>