<?php if (count($user_ordered_list)): ?>
  <ul>
    <?php
    foreach ($user_ordered_list as $item) :
      if (!empty($item)) :
    ?>
        <li><?php echo esc_html(trim($item)); ?></li>
    <?php
      endif;
    endforeach;
    ?>
  </ul>
<?php else : ?>
  <p><?php esc_html_e('No list found.', 'api-data-fetcher'); ?></p>
<?php endif; ?>
