<?php include_partial('use') ?>

<?php include_partial('tools', array('gn_blog_page' => $gn_blog_page)) ?>

<h1>Comparing version <?php echo $version_1 ?> to <?php echo $version_2 ?></h1>

<ul class="gn-tools">
  <li><?php echo link_to('&larr;'.__('Back'), 'gnBlogPage/versions?id='.$gn_blog_page->getId()) ?></li>
</ul>

<table class="gn-version-comparison">
  <thead>
    <tr>
      <th class="name">&nbsp;</th>
      <th class="comp1">Version <?php echo $version_1 ?><?php echo $current_version == $version_1 ? ' (Current)' : '' ?></th>
      <th class="comp2">Version <?php echo $version_2 ?><?php echo $current_version == $version_2 ? ' (Current)' : '' ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Title</strong></td>
      <td><?php echo $versions[1]['title'] ?></td>
      <td><?php echo $versions[2]['title'] ?></td>
    </tr>
    <tr>
      <td><strong>Content</strong></td>
      <td><?php echo nl2br($versions[1]['content']) ?></td>
      <td><?php echo nl2br($versions[2]['content']) ?></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo button_to('Revert to Version '. $version_1, 'gnBlogPage/Revert?id='.$gn_blog_page->getId().'&revert_to='.$version_1, array('class' => 'button'))?></td>
        <td><?php echo button_to('Revert to Version '. $version_2, 'gnBlogPage/Revert?id='.$gn_blog_page->getId().'&revert_to='.$version_2, array('class' => 'button'))?></td>
      </tr>
  </tbody>
</table>
