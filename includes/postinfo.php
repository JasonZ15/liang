<?php if (!is_single() && get_option('boutique_postinfo1') <> '') { ?>
	<p class="meta-info"><?php esc_html_e('Posted','Boutique'); ?> <?php if (in_array('author', get_option('boutique_postinfo1'))) { ?> <?php esc_html_e('by','Boutique'); ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('date', get_option('boutique_postinfo1'))) { ?> <?php esc_html_e('on','Boutique'); ?> <?php the_time(get_option('boutique_date_format')) ?><?php }; ?><?php if (in_array('categories', get_option('boutique_postinfo1'))) { ?> <?php esc_html_e('in','Boutique'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('boutique_postinfo1'))) { ?> | <?php comments_popup_link(esc_attr__('0 comments','Boutique'), esc_attr__('1 comment','Boutique'), '% '.esc_attr__('comments','Boutique')); ?><?php }; ?></p>
<?php } elseif (is_single() && get_option('boutique_postinfo2') <> '') { ?>
	<div class="post-meta">
		<?php global $query_string;
		$new_query = new WP_Query($query_string);
		while ($new_query->have_posts()) $new_query->the_post(); ?>
			<p class="meta-info">
				<?php esc_html_e('Posted','Boutique'); ?> <?php if (in_array('author', get_option('boutique_postinfo2'))) { ?> <?php esc_html_e('by','Boutique'); ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('date', get_option('boutique_postinfo2'))) { ?> <?php esc_html_e('on','Boutique'); ?> <?php the_time(get_option('boutique_date_format')) ?><?php }; ?><?php if (in_array('categories', get_option('boutique_postinfo2'))) { ?> <?php esc_html_e('in','Boutique'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('boutique_postinfo2'))) { ?> | <?php comments_popup_link(esc_attr__('0 comments','Boutique'), esc_attr__('1 comment','Boutique'), '% '.esc_attr__('comments','Boutique')); ?><?php }; ?>
			</p>
		<?php wp_reset_postdata() ?>
	</div> <!-- end .post-meta -->
<?php }; ?>