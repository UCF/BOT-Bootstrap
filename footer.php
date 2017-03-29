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
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-align-center">
						<img class="ucf-logo-footer" src="<?php echo THEME_STATIC_URL ?>/img/ucf-logo-footer.png" alt="UCF - University of Central Florida">
						<p class="footer-copyright">
							&copy; University of Central Florida
						</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?="\n".footer_()."\n"?>
</html>
