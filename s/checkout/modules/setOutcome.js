module.exports = function setOutcome(result) {
  var successElement = document.querySelector('.success');
  var errorElement = document.querySelector('.error');
  successElement.classList.remove('visible');
  errorElement.classList.remove('visible');

  fsub.valid = result.complete;

  var validSub = ($('#t_and_c').is(':checked') && result.complete);
  $('.signup-submit').prop('disabled', !validSub);

  if (typeof result.error !== 'undefined') {
    $('.error').text(result.error.message);
  } else {
    $('.error').text('');
  };
};
