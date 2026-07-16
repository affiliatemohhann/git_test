<?php
$section_id = $args['section_id'];
$title      = $args['title'];
$tabs       = $args['tabs'];
?>

<section id="<?= esc_attr($section_id); ?>" class="product-tabs">
  <header class="section-header">
    <h2><?= esc_html($title); ?></h2>
  </header>

  <div class="tabs-nav">
    <?php foreach ($tabs as $i => $tab): ?>
      <button
        class="tab-btn <?= $i === 0 ? 'active' : ''; ?>"
        data-tab="<?= esc_attr($section_id . '-' . $tab['id']); ?>">
        <?= esc_html($tab['label']); ?>
      </button>
    <?php endforeach; ?>
  </div>

  <div class="tabs-content">
    <?php foreach ($tabs as $i => $tab): ?>
      <?php
      $query_args = [
        'post_type'              => \WORTHIO_THEME\Inc\Schema_Utils::product_post_types(),
        'posts_per_page'         => 4,
        'tax_query'              => [
          [
            'taxonomy' => 'product_category',
            'field'    => 'slug',
            'terms'    => $tab['category'],
          ],
        ],
      ];

      if (!empty($tab['meta_key'])) {
        $query_args['meta_query'] = [
          'relation' => 'AND',
        ];
        $query_args['meta_query'][] = [
          'key'   => $tab['meta_key'],
          'value' => '1',
        ];
      }

      if (!empty($tab['orderby'])) {
        $query_args['orderby'] = $tab['orderby'];
        $query_args['order']   = 'DESC';
      }

      $product_ids = \WORTHIO_THEME\Inc\Schema_Utils::get_cached_product_ids(
        $query_args,
        'product_tab_' . sanitize_key($section_id . '_' . $tab['id'])
      );
      $products = \WORTHIO_THEME\Inc\Schema_Utils::get_posts_by_ids($product_ids);
      ?>

      <div
        id="<?= esc_attr($section_id . '-' . $tab['id']); ?>"
        class="tab-panel <?= $i === 0 ? 'active' : ''; ?>">
        
        <?php if (!empty($products)): ?>
          <div class="products-grid">
            <?php foreach ($products as $post): setup_postdata($post); ?>
              <?php get_template_part('parts/tabs/product-card'); ?>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p>No products found.</p>
        <?php endif; ?>

      </div>
      <?php wp_reset_postdata(); ?>
    <?php endforeach; ?>
  </div>
</section>
