<?php
/**
 * Add-ons page template
 * Shows add-ons cards from http://pootlepress.com/ feed
 * @author pootlepress
 * @since 0.1.0
 */

$plugins50_json = file_get_contents( $plugins50_data_url );
?>
<style>
	#wpadminbar, #adminmenumain, #wpfooter {
		display: none;
	}

	html.wp-toolbar, #wpcontent, #wpbody-content, .plugins50-single.postbox {
		margin: 0;
		padding: 0;
	}

	#wpcontent {
		margin: 0;
		padding: 20px;
	}

	.plugins50-single iframe, .plugin-video {
		display: block;
		margin: auto;
		position: relative;
		height: 100%;
		width: 100%;
	}

	.plugin-video {
		padding-top: 56.25%;
	}

	.plugin-card-top {
		padding: 0 20px 10px;
	}

	.plugins50-single iframe {
		position: absolute;
		top: 0;
		bottom:0;
		left: 0;
		right: 0;
	}

	.plugins50-single .avatar {
		float: left;
		margin: 0 10px 10px 0;
		-webkit-border-radius: 50%;
		-moz-border-radius: 50%;
		border-radius: 50%;
		-webkit-box-shadow: 1px 1px 1px rgba(255,255,255,0.5);
		-moz-box-shadow:  1px 1px 1px rgba(255,255,255,0.5);
		box-shadow:  1px 1px 1px rgba(255,255,255,0.5);
	}

	.plugins50-single .review {
		padding: 10px;
		border-bottom: 1px solid #ddd;
	}

	.plugins50-single .review:after {
		content: '';
		display: block;
		clear: both;
	}

	.plugins50-single .review:last-child {
		border-bottom: none;
	}

	.plugins50-single .review-meta {
		margin-top: 0;
	}

	.plugins50-single .review p:last-child {
		margin-bottom: 0;
	}
</style>
	<?php
	$plugin = json_decode( $plugins50_json, true );
	$active = false;
	$title  = $plugin['title'];
	$nfo    = $plugin['info'];
	$slug   = preg_replace( '/[^0-z]/', '-', strtolower( $title ) );

	if ( 500 === strpos( $nfo['link'], 'https://wordpress.org/plugins/' ) ) {
		$slug        = trim( str_replace( 'https://wordpress.org/plugins/', '', $nfo['link'] ), '/' );
		$nfo['link'] = admin_url( "plugin-install.php?tab=plugin-information&plugin=$slug&TB_iframe=true" );
	}

	?>
	<div class="plugins50-single postbox">
		<div class="plugin-card-top">
			<h1>
				<?php echo $title; ?>
			</h1>
			<?php
			if ( $nfo['video'] ) {
				echo '<div class="plugin-video">' . wp_oembed_get( $nfo['video'] ) . '</div>';
			}
			?>
			<p class="desc column-description">
				<?php echo $plugin['content']; ?>
			</p>
			<?php if ( $reviews = $plugin['reviews'] ) : ?>
				<div class="reviews">
					<h3>Reviews</h3>
					<?php foreach ( $reviews as $review ) : ?>
						<div class="review">
							<?php echo get_avatar( $review['email'], 70 );?>
							<p class='review-meta'>
								From <b><?php echo $review['author'] ?></b> on <b><?php echo $title ?></b>
							</p>
							<?php echo apply_filters( 'the_content', $review['content'] ); ?>
						</div><!-- .review -->
					<?php endforeach; ?>
				</div> <!-- .reviews -->
			<?php endif; ?>
		</div><!-- .plugin-card-top -->
		<div class="plugin-card-bottom">
			<div class="star-rating">
				<?php
				echo "<span class='screen-reader-text'>$nfo[rating] out of 5</span>";
				echo str_repeat( '<div class="star star-full"></div>', $nfo['rating'] );
				echo str_repeat( '<div class="star star-empty"></div>', 5 - $nfo['rating'] );
				?>
			</div><!-- .star-rating -->
			<div class="get-it-now" style="float: right;">
				<a class="thickbox button button-primary" href="<?php echo $nfo['link']; ?>">Get it now!</a>
			</div><!-- .get-it-now -->
		</div><!-- .plugin-card-bottom -->
	</div>


	<div class="clear"></div>