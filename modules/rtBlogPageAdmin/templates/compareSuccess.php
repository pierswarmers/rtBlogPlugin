<?php use_helper('I18N', 'Date', 'rtAdmin') ?>

<h1><?php echo __('Comparing Version') ?> <?php echo $version_1 ?> to <?php echo $version_2 ?></h1>

<?php slot('rt-tools') ?>
<?php include_partial('rtAdmin/standard_modal_tools', array('object' => $rt_blog_page))?>
<?php end_slot(); ?>

<table class="rt-version-comparison">
  <thead>
    <tr>
      <th class="name">&nbsp;</th>
      <th class="comp1"><?php echo __('Version') ?> <?php echo $version_1 ?><?php echo $current_version == $version_1 ? ' (Current)' : '' ?></th>
      <th class="comp2"><?php echo __('Version') ?> <?php echo $version_2 ?><?php echo $current_version == $version_2 ? ' (Current)' : '' ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($versions[1] as $name => $value): ?>
    <tr>
      <th><?php echo $name ?></th>
      <td><?php echo $versions[1][$name] ?></td>
      <td><?php echo $versions[2][$name] ?></td>
    </tr>
    <?php endforeach; ?>
      <tr>
        <td>&nbsp;</td>
        <td>
          <ul class="rt-admin-tools">
            <li><?php echo rt_ui_button('revert', 'rtBlogPageAdmin/Revert?id='.$rt_blog_page->getId().'&revert_to='.$version_1, 'arrowrefresh-1-e'); ?></li>
          </ul>
        <td>
          <ul class="rt-admin-tools">
            <li><?php echo rt_ui_button('revert', 'rtBlogPageAdmin/Revert?id='.$rt_blog_page->getId().'&revert_to='.$version_2, 'arrowrefresh-1-e'); ?></li>
          </ul>
        </td>
      </tr>
  </tbody>
</table>
