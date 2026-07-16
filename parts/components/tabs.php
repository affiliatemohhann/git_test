<?php 
get_template_part(
  'parts/tabs/product-tabs',
  null,
  [
    'section_id' => 'appliances-tabs',
    'title' => 'Popular Appliances',
    'tabs' => [
      [
        'id' => 'refrigerator',
        'label' => 'Refrigerators',
        'category' => 'refrigerator',
      ],
      [
        'id' => 'washing-machine',
        'label' => 'Washing Machines',
        'category' => 'washing-machines',
      ],
      [
        'id' => 'air-conditioner',
        'label' => 'Air Conditioners',
        'category' => '	air-conditions',
      ],
    ],
  ]
);

get_template_part(
  'parts/tabs/product-tabs',
  null,
  [
    'section_id' => 'mobile-tabs',
    'title' => 'Mobile Phones',
    'tabs' => [
      [
        'id' => 'popular-mobiles',
        'label' => 'Popular',
        'category' => 'phones',
        // 'meta_key' => 'is_popular',
      ],
      [
        'id' => 'latest-mobiles',
        'label' => 'Latest',
        'category' => 'phones',
        // 'orderby' => 'date',
      ],
      [
        'id' => 'upcoming-mobiles',
        'label' => 'Upcoming',
        'category' => 'phones',
        // 'meta_key' => 'is_upcoming',
      ],
    ],
  ]
);
?>