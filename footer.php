				</div>
			</div>
		<footer>
			<div class="footer-menu-wrapper">
				<div class="container">
					<div class="row footer-menu-wrapper">
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
							[Search Goes Here]
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-align-center">
						&copy; University of Central Florida
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?="\n".footer_()."\n"?>
</html>
