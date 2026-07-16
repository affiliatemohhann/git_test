<?php 
/* 
    Front Page Landing template 
    @package deals365
*/
get_header();?>
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            Arhive Page
                <?php the_content(); ?>
             
        </div>
        <div class="col-lg-3">
				<div class="p-2">
					<h4>Side bar</h4>
					<?php get_sidebar() ?>
				</div> 
        </div>
    </div>
</div>
<?php get_footer(); ?>