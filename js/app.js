$(document).foundation();

(function($) {
  // Toggle SQL Query on results page
  $('#toggle').click(function() {
    $('.sql-query').slideToggle();
  });

  // Run the advanced search accordion on mobile
  $('.form-accordion .section-wrap > h2').click(function() {
    $(this).next().slideToggle();
    $(this).toggleClass('active');
    return false;
  });

  // Welcome beta users!
  $('#betaMsg').slideDown(500);

})(jQuery);

// Toggle Original Data slider
function toggleOrig() {
  const left = $('#orig').css('left');

  $('#origBtn').toggleClass('active');

  // If other slider is open, close it
  if ($('#fixedBtn').hasClass('active')) {
    $('#fixedBtn').toggleClass('active');
  }

  if (left === '0px') {
    $('#orig').css('left', '-100%');
  }
  else {
    $('#fixed').css('right', '-100%');
    $('#orig').css('left', '0');
  }
}

// Toggle Cleaned Data slider
function toggleFixed() {
  const right = $('#fixed').css('right');

  $('#fixedBtn').toggleClass('active');

  // If other slider is open, close it
  if ($('#origBtn').hasClass('active')) {
    $('#origBtn').toggleClass('active');
  }

  if (right === '0px') {
    $('#fixed').css('right', '-100%');
  }
  else {
    $('#orig').css('left', '-100%');
    $('#fixed').css('right', '0');
  }
}
