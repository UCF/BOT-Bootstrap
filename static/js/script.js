var Generic = {};

Generic.defaultMenuSeparators = function($) {
	// Because IE sucks, we're removing the last stray separator
	// on default navigation menus for browsers that don't 
	// support the :last-child CSS property
	$('.menu.horizontal li:last-child').addClass('last');
};

Generic.removeExtraGformStyles = function($) {
	// Since we're re-registering the Gravity Form stylesheet
	// manually and we can't dequeue the stylesheet GF adds
	// by default, we're removing the reference to the script if
	// it exists on the page (if CSS hasn't been turned off in GF settings.)
	$('link#gforms_css-css').remove();
}

if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
		Webcom.slideshow($);
		Webcom.analytics($);
		Webcom.handleExternalLinks($);
		Webcom.loadMoreSearchResults($);
		
		/* Theme Specific Code Here */
		Generic.defaultMenuSeparators($);
		Generic.removeExtraGformStyles($);
	});
}else{console.log('jQuery dependancy failed to load');}