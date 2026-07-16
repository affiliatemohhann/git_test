<!-- 
 Header Main Menu Navigation 
 @package worthio
-->

<?php
$menu_class = \WORTHIO_THEME\Inc\Menus::get_instance();
$worthio_header_menu_id = $menu_class->get_menu_id('worthio-header-menu');
$header_menus = wp_get_nav_menu_items($worthio_header_menu_id);
?>

	<div class="wrt-hdr">
		<div class="wrt-container">
		<div class="hdr-bar">
			<div class="logo">
				<?php
				if (function_exists('the_custom_logo')) {
					the_custom_logo();
				}
				?>
			</div>
			<div class="wrt-srch">
				<?php get_search_form(); ?>
			</div>
			<div class="hdr-social-icons">
				<ul class="scl-lnks">
					<li><a href="#"><i class="dashicons dashicons-facebook-alt"></i></a></li>
					<li><a href="#"><i class="dashicons dashicons-twitter"></i></a></li>
					<li><a href="#"><i class="dashicons dashicons-instagram"></i></a></li>
					<li><a href="#"><i class="dashicons dashicons-youtube"></i></a></li>
				</ul>
			</div>
		</div>
		</div>

		<div class="main-nav-bg">
			<div class="wrt-container">
				<div class="wrt-main-nav">
				<nav class="navbar navbar-expand-lg">
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<?php
						if (! empty($header_menus) && is_array($header_menus)) {
						?>
							<ul class="navbar-nav me-auto">
								<?php
								foreach ($header_menus as $menu_item) {
									if (! $menu_item->menu_item_parent) {

										$child_menu_items   = $menu_class->get_child_menu_items($header_menus, $menu_item->ID);
										$has_children       = ! empty($child_menu_items) && is_array($child_menu_items);
										$has_sub_menu_class = ! empty($has_children) ? 'has-submenu' : '';
										$link_target        = ! empty($menu_item->target) && '_blank' === $menu_item->target ? '_blank' : '_self';

										// Note_: Similar to $menu_item->target, there are other keys available in the $menu_item, such as classes. You can more key values if you need.

										if (! $has_children) {
								?>
											<li class="nav-item">
												<a class="nav-link" href="<?php echo esc_url($menu_item->url); ?>"
													target="<?php echo esc_attr($link_target); ?>"
													title="<?php echo esc_attr($menu_item->title); ?>">
													<?php echo esc_html($menu_item->title); ?>
												</a>
											</li>
										<?php
										} else {
										?>
											<li class="nav-item dropdown">
												<a class="nav-link dropdown-toggle" href="<?php echo esc_url($menu_item->url); ?>"
													id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
													aria-expanded="false" target="<?php echo esc_attr($link_target); ?>"
													title="<?php echo esc_attr($menu_item->title); ?>">
													<?php echo esc_html($menu_item->title); ?>
												</a>
												<div class="dropdown-menu" aria-labelledby="navbarDropdown">
													<?php
													foreach ($child_menu_items as $child_menu_item) {
														$link_target = ! empty($child_menu_item->target) && '_blank' === $child_menu_item->target ? '_blank' : '_self';
													?>
														<a class="dropdown-item"
															href="<?php echo esc_url($child_menu_item->url); ?>"
															target="<?php echo esc_attr($link_target); ?>"
															title="<?php echo esc_attr($child_menu_item->title); ?>">
															<?php echo esc_html($child_menu_item->title); ?>
														</a>
													<?php
													}
													?>
												</div>
											</li>
										<?php
										}
										?>
								<?php
									}
								}
								?>
							</ul>
						<?php
						}
						?>

					</div>
				</nav>
				</div>
			</div>
		</div>
	</div>
