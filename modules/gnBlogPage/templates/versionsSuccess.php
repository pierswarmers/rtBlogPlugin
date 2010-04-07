<?php include_partial('use') ?>
        
<h1>Comparing Versions: <?php echo $gn_blog_page->getTitle() ?></h1>

<ul class="gn-tools">
  <li><?php echo link_to('&larr;'.__(' Back'), 'gnBlogPage/show?id='.$gn_blog_page->getId()) ?></li>
</ul>

<form action="<?php echo url_for('gnBlogPage/compare?id='.$gn_blog_page->getId()) ?>">
  <table class="stretch">
    <thead>
      <tr>
        <th>Version</th>
        <th>Compare 1.</th>
        <th>Compare 2.</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php for($i = $gn_blog_page->getVersion(); $i > 0; $i--): ?>
      <tr>
        <td><?php echo $i ?></td>
        <td><input type="radio" name="version1" value="<?php echo $i ?>" /></td>
        <td><input type="radio" name="version2" value="<?php echo $i ?>" /></td>
        <td><?php echo link_to('Revert to Version '. $i, 'gnBlogPage/Revert?id='.$gn_blog_page->getId().'&revert_to='.$i, array('class' => 'button small'))?></td>
      <?php endfor; ?>
    </tbody>
  </table>
<input type="submit" value="Compare" class="button" />
</form>
