		<footer>
			<div class="footer-menu-wrapper bg-default py-3">
				<div class="container">
					<div class="row">
						<div class="col-md-8 d-flex align-items-center">
						<?php
							echo wp_nav_menu(array(
								'theme_location' => 'footer-menu',
								'container'      => 'false',
								'menu_class'     => 'menu list-unstyled list-inline mb-0',
								'menu_id'        => 'footer-menu',
								'walker'         => new Bootstrap_Walker_Nav_Menu()
							));
						?>
						</div>
						<div class="col-md-4">
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?php echo "\n".footer_()."\n"?>
</html>
