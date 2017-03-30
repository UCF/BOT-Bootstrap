
var meetingTabs = function($) {
  var $sel = $('#year_select');

  if ( $sel ) {
    $sel.change(function(e) {
      var val = $(e.target).val();
      $('#meeting-year').text(val);
      $('div[id^=panel_].active').removeClass('active');
      $('#panel_' + val).addClass('active');
    });
  }
};

if (jQuery !== 'undefined') {
  jQuery(document).ready(function($) {
    meetingTabs($);
  });
}
