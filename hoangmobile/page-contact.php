<?php
/*
  Template Name: Page Contact
 */
?>
<?php get_header(); ?>

<?php global $shortname; ?>

<div class="center">
    <div id="showloadingim" style="display: none" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>

    <div class="addrWhole pdl10">
        <div class="redTitle"><?php the_title(); ?></div>
        <div class="colHalf">
            <?php while (have_posts()) : the_post(); ?>
            <div><?php the_content(); ?></div>
            <?php endwhile; ?>   
        </div><!--colHalf-->
        
        <div class="colHalf">
            <div class="colMap">
                <div class="google-maps"><?php echo stripslashes(get_option($shortname . '_gmaps')); ?></div>
                <!--colMap-->
            </div><!--colHalf-->

        </div>
    </div>
</div>

</div>

<?php get_footer(); ?>