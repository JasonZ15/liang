<?php 
/*
Template Name: Blog Page
*/
?>

<?php
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;

$et_ptemplate_blogstyle = isset( $et_ptemplate_settings['et_ptemplate_blogstyle'] ) ? (bool) $et_ptemplate_settings['et_ptemplate_blogstyle'] : false;

$et_ptemplate_showthumb = isset( $et_ptemplate_settings['et_ptemplate_showthumb'] ) ? (bool) $et_ptemplate_settings['et_ptemplate_showthumb'] : false;

$blog_cats = isset( $et_ptemplate_settings['et_ptemplate_blogcats'] ) ? (array) $et_ptemplate_settings['et_ptemplate_blogcats'] : array();
$et_ptemplate_blog_perpage = isset( $et_ptemplate_settings['et_ptemplate_blog_perpage'] ) ? (int) $et_ptemplate_settings['et_ptemplate_blog_perpage'] : 10;
?>

<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs','index'); ?>

<div id="main-content" <?php if($fullwidth) echo ('class="fullwidth"');?>>
	<div id="main-content-bg">
		<div id="main-content-bottom-bg" class="clearfix">
			<div id="left-area">
				<div id="main-products" class="clearfix">
					<?php get_template_part('loop','page'); ?>
					
					<div id="et_pt_blog">
						<?php $cat_query = ''; 
						if ( !empty($blog_cats) ) $cat_query = '&cat=' . implode(",", $blog_cats);
						else echo '<!-- blog category is not selected -->'; ?>
						<?php query_posts("showposts=$et_ptemplate_blog_perpage&paged=$paged" . $cat_query); ?>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
							<div class="et_pt_blogentry clearfix">
								<h2 class="et_pt_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								
								<p class="et_pt_blogmeta"><?php esc_html_e('Posted','Boutique'); ?> <?php esc_html_e('by','Boutique'); ?> <?php the_author_posts_link(); ?> <?php esc_html_e('on','Boutique'); ?> <?php the_time(esc_attr(get_option('boutique_date_format'))) ?> <?php esc_html_e('in','Boutique'); ?> <?php the_category(', ') ?> | <?php comments_popup_link(esc_attr__('0 comments','Boutique'), esc_attr__('1 comment','Boutique'), '% '.esc_attr__('comments','Boutique')); ?></p>
								
								<?php $thumb = '';
								$width = 184;
								$height = 184;
								$classtext = '';
								$titletext = get_the_title();

								$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
								$thumb = $thumbnail["thumb"]; ?>
								
								<?php if ( $thumb <> '' && !$et_ptemplate_showthumb ) { ?>
									<div class="et_pt_thumb alignleft">
										<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
										<a href="<?php the_permalink(); ?>"><span class="overlay"></span></a>
									</div> <!-- end .thumb -->
								<?php }; ?>
								
								<?php if (!$et_ptemplate_blogstyle) { ?>
									<p><?php truncate_post(550);?></p>
									<!--<a href="<?php the_permalink(); ?>" class="readmore"><span><?php esc_html_e('read more','Boutique'); ?></span></a>-->
								<?php } else { ?>
									<?php the_content(''); ?>
								<?php } ?>
							</div> <!-- end .et_pt_blogentry -->
							
						<?php endwhile; ?>
							<div class="page-nav clearfix">
								<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
								else { ?>
									 <?php get_template_part('includes/navigation.php'); ?>
								<?php } ?>
							</div> <!-- end .entry -->
						<?php else : ?>
							<?php get_template_part('includes/no-results'); ?>
						<?php endif; wp_reset_query(); ?>
					
					</div> <!-- end #et_pt_blog -->
					<a class="single-addtocart addtogallery" href="<?php echo get_permalink( 7 ); ?>"><?php esc_html_e('我要提问', 'Boutique'); ?></a>
				</div> <!-- end #main-products -->
			</div> <!-- end #left-area -->
			
			<?php if (!$fullwidth) get_sidebar(); ?>
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>