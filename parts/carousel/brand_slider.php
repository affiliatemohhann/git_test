  <?php 
   // $icon_id = get_field( 'category_icon', 'product_brand' . $term->term_id );
   // $icon_url = $icon_id ? wp_get_attachment_image_url( $icon_id, '' ) : '';
  ?>
  <div class="swiper hm-catg-slder">   
    <div class="swiper-wrapper">
      <?php
      $terms = get_terms([
      'taxonomy' => 'product_brand',
      'hide_empty' => true,
      'number' => 6,
      ]);
      
      foreach ($terms as $term): ;
       $icon_id = \WORTHIO_THEME\Inc\Schema_Utils::get_term_value( [ 'brand_icon' ], $term->term_id, 'product_brand' );
      $icon = $icon_id ? wp_get_attachment_image_url( $icon_id, '' ) : '';
      
      ?>
      <div class="swiper-slide">
        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="category-item" >
            <article class="post-card">
                <?php if ( $icon ) : ?>
            <img
              src="<?php echo esc_url( $icon ); ?>"
              alt="<?php echo esc_attr( $term->name ); ?>"
              loading="lazy"
            />
          <?php endif; ?>
          <h3><?php echo esc_html( $term->name ); ?></span>
            </article>     
        </a>
  </div>
      <?php endforeach; ?>
    </div>
    <!-- <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div> -->
  </div>


