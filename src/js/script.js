var headerImages = function($) {
  var $headerImg = $('.header-image'),
      $window = $(window),
      mdImage = $headerImg.data('header-md'),
      smImage = $headerImg.data('header-sm'),
      breakpoint = 767,
      resizeTimer = null,
      debounce = 250;

  var onResize = function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(onResizeEnd, debounce);
  };

  var onResizeEnd = function() {
    if ($window.width() > breakpoint) {
      $headerImg.css('background-image', 'url(' + mdImage + ')');
    } else {
      $headerImg.css('background-image', 'url(' + smImage + ')');
    }
  };

  $window.resize(onResize);

};

if (jQuery !== 'undefined') {
  jQuery(document).ready(function($) {
    headerImages($);
  });
}
