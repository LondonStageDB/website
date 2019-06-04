$(function() {
  $('#addActor').on('click', function() {
    let html = '<input type="text" class="actor actor-search" name="actor[]" id="actor" onKeyPress="checkEnter(event)">';
    $('#actors').append(html);
    $('#actSwitch').prop('disabled', false);
    $(".actor-search").autocomplete({
      source: "/includes/act.php",
    });
  });

  $('#addRole').on('click', function() {
    let html = '<input type="text" class="role role-search" name="role[]" id="role" onKeyPress="checkEnter(event)">';
    $('#roles').append(html);
    $('#roleSwitch').prop('disabled', false);
    $(".role-search").autocomplete({
      source: "/includes/role.php",
    });
  });

  // Enable Mon/Day fields on initial page load based on what is filled in
  if ($('#startYear').val()) {
    $('#startMonth').prop('disabled', false);
    if ($('#startMonth').val()) $('#startDay').prop('disabled', false);
  }
  if ($('#endYear').val()) {
    $('#endMonth').prop('disabled', false);
    if ($('#endMonth').val()) $('#endDay').prop('disabled', false);
  }

  // Hide second date row if dateType not set to 1 ('Between') on initial page load
  if ($('#dateType').val() !== '1') {
    $('.year-title').hide();
    $('.end-year').hide();
  } else {
    $('.year-title').show();
    $('.end-year').show();
  }

  // Enable Mon/Day fields as previous date fields filled in
  $('#startYear').on('change', function() {
    if ($('#startYear').val()) $('#startMonth').prop('disabled', false);
  });
  $('#startMonth').on('change', function() {
    if ($('#startMonth').val()) $('#startDay').prop('disabled', false);
  });
  $('#endYear').on('change', function() {
    if ($('#endYear').val()) $('#endMonth').prop('disabled', false);
  });
  $('#endMonth').on('change', function() {
    if ($('#endMonth').val()) $('#endDay').prop('disabled', false);
  });

  // Only show dateType option if has JS
  $('.date-type.hide').removeClass('hide');

  // Hide second date row if dateType not set to 1 ('Between')
  $('#dateType').change(function() {
    if ($('#dateType').val() !== '1') {
      $('.year-title').hide();
      $('.end-year').hide();
    } else {
      $('.year-title').show();
      $('.end-year').show();
    }
  });

  // Initialize Autocomplete Fields
  $("#performance").autocomplete({
    source: "/includes/perf.php",
  });
  $("#author").autocomplete({
    source: "/includes/auth.php",
  });
  $(".actor-search").autocomplete({
    source: "/includes/act.php",
  });
  $(".role-search").autocomplete({
    source: "/includes/role.php",
  });

  jQuery.ui.autocomplete.prototype._resizeMenu = function () {
    var ul = this.menu.element;
    ul.outerWidth(this.element.outerWidth());
  }

});

function checkEnter(e){
  var characterCode;

  if(e && e.which){ //if which property of event object is supported (NN4)
    e = e;
    characterCode = e.which; //character code is contained in NN4's which property
  }
  else{
    e = event;
    characterCode = e.keyCode; //character code is contained in IE's keyCode property
  }

  if(characterCode == 13){ //if generated character code is equal to ascii 13 (if enter key)
    document.forms[0].submit(); //submit the form
    return false;
  }
  else{
    return true;
  }
}
