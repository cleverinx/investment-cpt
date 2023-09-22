<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<!--if attributes are being passed -->

	<?php

		if ( ! empty( $attributes['selectedCategories'] ) ) : ?>

			<?php
			//create category array to store all categories
			$categories = array();
			?>
			<div class="investment-block__wrapper">
				<div class="investment-block__categories">
					<?php foreach ( $attributes['selectedCategories'] as $category ) : ?>
						<?php $category = get_term( $category, 'investment-category' ); ?>

						<?php $categories[] = $category ?>

						<a data-target="#investment-<?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
					<?php endforeach; ?>
				</div>


				<?php foreach ( $categories as $category ) : ?>
					<div class="investment-category__wrapper" id="investment-<?php echo $category->slug; ?>">

						<!--	get all posts from each category-->
						<?php $posts = get_posts( array(
							'post_type'      => 'investment',
							'posts_per_page' => - 1,
							'tax_query'      => array(
								array(
									'taxonomy' => 'investment-category',
									'field'    => 'term_id',
									'terms'    => $category->term_id,
								),
							),
						) ); ?>
						<!--loop through posts and get the custom fields investment_logo and the array of text items investment_text-->

						<?php foreach ( $posts as $post ) : ?>
							<?php $logo = get_post_meta( $post->ID, 'investment_logo', true ); ?>

							<?php $alt = get_post_meta( attachment_url_to_postid( $logo ), '_wp_attachment_image_alt', true ); ?>

							<?php $text = get_post_meta( $post->ID, 'investment_text', true ); ?>

							<?php if ( ! empty( $logo ) ) : ?>
								<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo $alt; ?>"/>
							<?php endif; ?>
							<!-- loop through array of text items and output-->
							<?php if ( ! empty( $text ) ) : ?>
								<ul>
								<?php foreach ( $text as $item ) : ?>
									<li><?php esc_html_e( $item, 'investment-block' ); ?></li>
									<!--output with esc_html_e-->


								<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

		<?php endif;
 ?>

</div>


