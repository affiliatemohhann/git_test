<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.

 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

 <div <?php echo get_block_wrapper_attributes(); ?>>
    <h2><?php echo esc_html( $attributes['message'] ); ?></h2>   
</div> 

 <?php
  $post_id = get_the_ID();
  $all_fields = get_fields($post_id);

  $editorial_score = $all_fields['wio_editorial_score'] ?? ''; 
  $pros_raw  = $all_fields['wio_pros_item'] ?? ''; 
  $cons_raw  = $all_fields['wio_cons_item'] ?? '';
  $pros = $pros_raw ? explode("\n", $pros_raw) : [];
  $cons = $cons_raw ? explode("\n", $cons_raw) : [];
  $short_summary = $all_fields['wio_short_summary'] ?? '';
  $performance_score = $all_fields['wio_performance_score'] ?? '';
  $camera_score = $all_fields['wio_camera_score'] ?? '';
  $battery_score = $all_fields['wio_battery_score'] ?? '';
  $display_score = $all_fields['wio_display_score'] ?? '';
  $value_score = $all_fields['wio_value_for_money'] ?? '';
  $final_verdict = $all_fields['wio_final_verdict'] ?? '';
  
// $editorial_score   = get_field('editorial_score');
// $pros_raw          = get_field('pros_item');
// $cons_raw          = get_field('cons_item');
// $pros = $pros_raw ? explode("\n", $pros_raw) : [];
// $cons = $cons_raw ? explode("\n", $cons_raw) : [];
// $short_summary     = get_field('short_summary');
// $performance_score = get_field('performance_score');
// $camera_score      = get_field('camera_score');
// $battery_score     = get_field('battery_score');
// $display_score     = get_field('display_score');
// $value_score       = get_field('value_for_money');
// $final_verdict     = get_field('final_verdict');

//if (!$editorial_score) return;
?>

<div class="product-review-block" <?php echo get_block_wrapper_attributes(); ?>>
    <div class="editorial-score">
        <h3>Our Score: <?php echo esc_html($editorial_score); ?>/10</h3>
    </div>

    <?php if ($short_summary): ?>
        <div class="short-summary">
            <p><?php echo esc_html($short_summary); ?></p>
        </div>
    <?php endif; ?>

    <div class="sub-scores">
        <ul>
            <li>
               <span><?php echo worthio_icon('performance', 'icon-green'); ?></span>   
              Performance:  <?php echo esc_html($performance_score); ?>/10</li>
            <li>
                <span><?php echo worthio_icon('camera', 'icon-green'); ?></span>   
                Camera: <?php echo esc_html($camera_score); ?>/10</li>
            <li>
             <span><?php echo worthio_icon('battery', 'icon-green'); ?></span>       
            Battery: <?php echo esc_html($battery_score); ?>/10</li>
            <li>
                <span><?php echo worthio_icon('display', 'icon-green'); ?></span>   
                Display: <?php echo esc_html($display_score); ?>/10</li>
            <li>
                
             <span><?php echo worthio_icon('cash', 'icon-green'); ?></span>         
            Value for Money: <?php echo esc_html($value_score); ?>/10</li>
        </ul>
    </div>

    <?php if ($pros): ?>
    <div class="pros">
        <h4>Pros</h4>
         <span><?php echo worthio_icon('pros', 'icon-green'); ?></span>   
        <ul>
            
            <?php foreach ($pros as $pro) : ?>
                <li><?php echo esc_html(trim($pro)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($cons): ?>
    <div class="cons">
        <h4>Cons</h4>
        <span><?php echo worthio_icon('cons', 'icon-red'); ?></span>   
        <ul>
            <?php foreach ($cons as $con): ?>                
                <li><?php echo esc_html(trim($con)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($final_verdict): ?>
    <div class="final-verdict">
        <h3>Final Verdict</h3>
         <span><?php echo worthio_icon('verdict', 'icon-red'); ?></span>   
        <?php echo wp_kses_post($final_verdict); ?>
    </div>
    <?php endif; ?>
</div>




