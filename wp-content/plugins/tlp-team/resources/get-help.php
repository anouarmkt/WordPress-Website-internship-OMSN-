<?php
/**
 * Get help view.
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>

<div class="wrap">
	<div id="tlp-team-get-help-wrapper">
		<div class="tlp-team-setting-container">

			<div id="support" class="rt-document-box">
				<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
				<div class="rt-box-content">
					<h3 class="main-title"> <?php esc_html_e( 'Thank you for installing Team Plugin', 'tlp-team' ); ?>  </h3>
					<div class="rt-video-wrapper">
						<div class="rt-video-col">
							<div class="rt-responsive-iframe">
								<iframe width="800" height="450" src="https://www.youtube.com/embed/oJ_AYCk-iXU" title="How To Create Team Page Using ShortCode With WordPress Team Members Showcase Plugin" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
						</div>
						<div class="rt-video-col">
							<div class="rt-responsive-iframe">
								<iframe width="800" height="450" src="//www.youtube.com/embed/S4tXNPws9XY" title="How To Create Team Page Using Elementor Addon With WordPress Team Members Showcase Plugin" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="rtteam-pro-box rt-document-box">
				<div class="rt-box-icon"><i class="dashicons dashicons-megaphone"></i></div>
				<div class="rt-box-content">
					<h3 class="rt-box-title">Features of the pro version of the team member plugin</h3>
					<div class="rtteam-feature-list">
						<ul>
							<li><i class="dashicons dashicons-saved"></i> 30+ Additional Layouts.</li>
							<li><i class="dashicons dashicons-saved"></i> Grid with Margin or no Margin or Specific Gutter.</li>
							<li><i class="dashicons dashicons-saved"></i> Detail Page Multi Popup with next or preview button.</li>
							<li> <i class="dashicons dashicons-saved"></i>  Detail Page Single Popup </li>
							<li> <i class="dashicons dashicons-saved"></i>  Detail Page Smart Popup </li>
							<li> <i class="dashicons dashicons-saved"></i>  Multiple Pagination Option <br>( Ajax Number Pagination, Load more button, Load more on scroll  )</li>
						</ul>
						<ul>
							<li> <i class="dashicons dashicons-saved"></i> 4 Elementor Widgets with lots of Layouts & Customizations </li>
							<li> <i class="dashicons dashicons-saved"></i> Additional Image for Gallery </li>
							<li> <i class="dashicons dashicons-saved"></i> Grayscale Profile image </li>
							<li> <i class="dashicons dashicons-saved"></i> Custom image size for Profile image </li>
							<li> <i class="dashicons dashicons-saved"></i> Dray & Drop Member Ordering </li>
							<li> <i class="dashicons dashicons-saved"></i> Grid Filter Layout Filter style Dropdown or Button </li>
							<li> <i class="dashicons dashicons-saved"></i> And much more features are available  </li>
						</ul>
					</div>
				</div>
			</div>

			<div class="rtteam-call-to-action" style="background-image: url('<?php echo esc_url( rttlp_team()->assets_url() ); ?>/images/admin/banner.png')">
				<a href="<?php echo esc_url( rttlp_team()->pro_version_link() ); ?>" target="_blank" class="rt-update-pro-btn">
					Update Pro To Get More Features
				</a>
			</div>
			<div class="rt-document-box" style="margin-bottom: 20px;">
				<div class="rt-box-icon"><i class="dashicons dashicons-thumbs-up"></i></div>
				<div class="rt-box-content">
					<h3 class="rt-box-title">Happy clients of Team plugin</h3>
					<div class="rtteam-testimonials">
						<div class="rtteam-testimonial">
							<p>I was very please with how easily I was able to custom the plugin to match the theme and that it integrates nicely with the Genesis framework. And the support is quick and very helpful.</p>
							<div class="client-info">
								<img src="https://secure.gravatar.com/avatar/cff6168e0a20206eef5a689179dc24f8?s=200&d=retro&r=g">
								<div>
									<div class="rtteam-star">
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
									</div>
									<span class="client-name">Cara</span>
								</div>
							</div>
						</div>
						<div class="rtteam-testimonial">
							<p>Great plugin – excellent customer service. The plugin works nicely and if you do run into issues, their support is excellent! Thumbs up!</p>
							<div class="client-info">
								<img src="https://secure.gravatar.com/avatar/fa74f1031be978e96d0f1bc4a090736f?s=150&d=retro&r=g">
								<div>
									<div class="rtteam-star">
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
										<i class="dashicons dashicons-star-filled"></i>
									</div>
									<span class="client-name">Helmakranz</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="doc-section" style="margin-bottom: 20px;">
				<div class="rt-document-box">
					<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
					<div class="rt-box-content">
						<h3 class="rt-box-title">Documentation</h3>
							<p>Get started by spending some time with the documentation we included step by step process with screenshots with video.</p>
							<a href="<?php echo esc_url( rttlp_team()->documentation_link() ); ?>" target="_blank" class="rt-admin-btn">Documentation</a>
					</div>
				</div>
				<div class="rt-document-box">
					<div class="rt-box-icon"><i class="dashicons dashicons-sos"></i></div>
					<div class="rt-box-content">
						<h3 class="rt-box-title">Need Help?</h3>
						<p>Stuck with something? Please create a
						<a href="<?php echo esc_url( rttlp_team()->ticket_link() ); ?>">ticket here</a> or post on <a href="<?php echo esc_url( rttlp_team()->fb_link() ); ?>">facebook group</a>. For emergency case join our <a href="<?php echo esc_url( rttlp_team()->radius_link() ); ?>">live chat</a>.</p>
						<a href="<?php echo esc_url( rttlp_team()->ticket_link() ); ?>" target="_blank" class="rt-admin-btn">Get Support</a>
					</div>
				</div>
				<div class="rt-document-box">
					<div class="rt-box-icon"><i class="dashicons dashicons-smiley"></i></div>
					<div class="rt-box-content">
						<h3 class="rt-box-title">Happy Our Work?</h3>
						<p>If you happy with <strong>Team</strong> plugin, please add a rating. It would be glad to us.</p>
						<a href="<?php echo esc_url( rttlp_team()->review_link() ); ?>" class="rt-admin-btn" target="_blank">Post Review</a>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>
