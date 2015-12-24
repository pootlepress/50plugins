<?php
/**
 * Add-ons page template
 * Shows add-ons cards from http://pootlepress.com/ feed
 * @author pootlepress
 * @since 0.1.0
 */

$plugins50_json = file_get_contents( 'http://pootle.wp.iex.uno/wp-json/plgn-dir/plugins/' );

$active_addons = get_option( 'plugins50_active_addons', '' );
add_thickbox();
?>
<div class="wrap">
	<h1 style="padding: 7px 0 25px;font-size: 35px;">50 Plugins</h1>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'plugins50_active_addons' );
		settings_errors();

		$plugins50 = json_decode( $plugins50_json, true );

		foreach ( $plugins50 as $cat => $plugins ) {

			echo '<div class="postbox" style="padding: 16px;">';
			echo '<h1 style="padding: 0 0 7px;margin: auto;">' . strip_tags( $cat ) . '</h1>';
			echo '<div class="cool-plugins">';
			foreach ( $plugins as $plugin ) {
				$active = false;
				$title  = $plugin['title'];
				$nfo    = $plugin['info'];

				if ( 0 === strpos( $nfo['link'], 'https://wordpress.org/plugins/' ) ) {
					$slug        = str_replace( 'https://wordpress.org/plugins/', '', $nfo['link'] );
					$nfo['link'] = admin_url( "plugin-install.php?tab=plugin-information&plugin=$slug&TB_iframe=true" );
				}

				?>
				<div class="plugin-card plugin-card-jetpack">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a href="<?php echo $plugin['url']; ?>?TB_iframe=true" class="thickbox">
									<?php echo $title; ?>
									<img src="<?php echo $plugin['img']; ?>" class="plugin-icon" alt="">
								</a>
							</h3>
						</div>
						<div class="desc column-description">
							<p><?php echo $plugin['excerpt']; ?></p>
							<a href="<?php echo $plugin['url']; ?>?TB_iframe=true" class="thickbox">More Details</a>
						</div>
					</div>
					<div class="plugin-card-bottom">
						<div class="vers column-rating">
							<div class="star-rating">
								<span class="screen-reader-text"><?php echo $nfo['rating'] ?> out of 5</span>
								<?php
								echo str_repeat( '<div class="star star-full"></div>', $nfo['rating'] );
								echo str_repeat( '<div class="star star-empty"></div>', 5 - $nfo['rating'] );
								?>
							</div>
						</div>
						<div class="column-compatibility">
							<a class="thickbox button button-primary" href="<?php echo $nfo['link']; ?>?TB_iframe=true">Get <?php echo $title; ?></a>
						</div>
					</div>
				</div>

				<?php
			}
			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';
		}
		?>
		<div class="clear"></div>
	</form>
</div>