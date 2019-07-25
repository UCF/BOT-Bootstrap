var $headerImg;

const headerImages = function ($) {
  console.log('load');
  let $window = $(window),
    mdImage = $headerImg.data('header-md'),
    smImage = $headerImg.data('header-sm'),
    breakpoint = 767,
    resizeTimer = null,
    debounce = 250;

  const onResize = function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(onResizeEnd, debounce);
  };

  var onResizeEnd = function () {
    if ($window.width() > breakpoint) {
      $headerImg.css('background-image', `url(${mdImage})`);
    } else {
      $headerImg.css('background-image', `url(${smImage})`);
    }
  };

  $window
    .load(onResize)
    .resize(onResize);

};

const meetingTabs = function ($) {
  const $sel = $('#year_select');

  if ($sel) {
    $sel.change((e) => {
      const val = $(e.target).val();
      $('#meeting-year').text(val);
      $('div[id^=panel_].active').removeClass('active');
      $(`#panel_${val}`).addClass('active');
    });
  }
};

const peopleImages = function ($) {
  $figures = $('figure.person-figure');

  if ($figures.length) {
    $figures.matchHeight();
  }
};

if (jQuery !== 'undefined') {
  var $headerImg = $('.media-header-content');

  jQuery(document).ready(($) => {
    headerImages($);
    meetingTabs($);
    peopleImages($);
  });
}
