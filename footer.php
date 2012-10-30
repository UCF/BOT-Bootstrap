			<?=wp_nav_menu(array(
				'theme_location' => 'footer-menu', 
				'container'      => 'false', 
				'menu_class'     => 'nav nav-pills', 
				'menu_id'        => 'footer-menu', 
				'fallback_cb'    => false,
				'depth'          => 1,
				'walker'         => new Bootstrap_Walker_Nav_Menu()
				));
			?>
			<div id="footer">
				<div class="row">
					<div class="span9">
						<a class="ignore-external" href="http://www.ucf.edu"><img src="<?=THEME_IMG_URL?>/logo.png" alt="" title="" /></a>
					</div>
					<div class="span3">
						<?php $options = get_option(THEME_OPTIONS_NAME);?>
						<?php if($options['site_contact'] or $options['organization_name']):?>
						<p>
							Site maintained by the <br />
							<?php if($options['site_contact'] and $options['organization_name']):?>
							<a href="mailto:<?=$options['site_contact']?>"><?=$options['organization_name']?></a>
							<?php elseif($options['site_contact']):?>
							<a href="mailto:<?=$options['site_contact']?>"><?=$options['site_contact']?></a>
							<?php elseif($options['organization_name']):?>
							<?=$options['organization_name']?>
							<?php endif;?>
						</p>
						<?php endif;?>
						<p>&copy; University of Central Florida</p>
					</div>
				</div>
			</div>
		</div>
	</body>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
</html>