<?php if ($sf_user->hasFlash('error')): ?>
<p class="error"><?php echo $sf_user->getFlash('error'); ?></p>
<?php elseif ($sf_user->hasFlash('success')): ?>
<p class="success"><?php echo $sf_user->getFlash('success'); ?></p>
<?php endif; ?>