		<footer>
			<div class="footer-menu-wrapper">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
						<?php
							echo wp_nav_menu(array(
								'theme_location' => 'header-menu',
								'container'      => 'false',
								'menu_class'     => 'menu list-unstyled list-inline',
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
	<?="\n".footer_()."\n"?>
</html>
