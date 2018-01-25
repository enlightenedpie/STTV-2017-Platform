module.exports = function setToken(result) {
  if (result.error) {
    $('#checkout_modal_overlay').fadeOut(1, function() {
      $('.error', cModal).text(result.error.message);
    });
    console.log(result.error);
    return false;
  }
  cModal.animate({
    scrollTop: 0
  }, 1);
  cModal.css('overflow', 'hidden');
  $('#checkout_modal_overlay').fadeIn(1, function() {
    $(this).prepend('<h2 style="margin-top:4em">PROCESSING...</h2>');
    $('span', this).text('This could take a minute if you have a slow connection.');
  });
  console.log(result, "pre POST");
  data.token = result.token.id;

  $.post(stajax.ajaxURL, data, function(response) {
    var action = {};
    if (response.success) {
      action.ST = function() {
        window.location.replace(response.data);
      };
      action.action = 'Success';
      action.color = 'olive';
      action.msg = 'You will be redirected shortly';
      action.icon = 'done';
    } else {
      action.ST = function() {
        cModal.modal('close');
      };
      action.action = 'Error';
      action.color = 'red-text text-darken-3';
      action.msg = response.data.message;
      action.icon = 'report_problem';
    }
    var appended = '<div>';
    appended += '<h2 class="' + action.color + '">';
    appended += '<i class="material-icons">' + action.icon + '</i> ';
    appended += action.action + '</div>';
    appended += '<small>' + action.msg + '</small><br/>';
    appended += (!response.success)
      ? '<small>err: ' + response.data.error + '</small>'
      : '';
    console.log(response);
    $('.modal-content', cModal).empty();
    $('.modal-content', cModal).append(appended);
    setTimeout(function() {
      action.ST()
    }, 3000);
  });
};
