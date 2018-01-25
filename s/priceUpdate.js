var priceUpdate = (function() {
  return {
    priceUpdate: function() {
      plan.shipping = ($('#sttv_digital_book').is(':checked'))
        ? 1195
        : 0;
      var price = parseFloat(plan.price);
      var taxed = (25 * (plan.tax / 100)) * 100;
      var totals = (((price - (price * (plan.discp / 100)) - plan.disc) + taxed) + plan.shipping);

      if (0 < totals) {
        var btnmsg = 'Pay $' + (
        totals / 100).toFixed(2) + ' now!';
      } else {
        var btnmsg = 'Sign up for FREE now!';
      }
      $('button.signup-submit span').fadeOut(100, function() {
        $(this).text(btnmsg);
      }).fadeIn(100);

      var table = $('table#totals_table');

      $('tbody', table).fadeOut(100, function() {
        $(this).empty().append('<tr><td colspan="2">' + plan.name + '</td><td style="text-align:right">' + (
        price / 100).toFixed(2) + '</td></tr>');

        if (0 < plan.disc) {
          var discprice = plan.disc;
        } else if (0 < plan.discp) {
          var discprice = (price * (plan.discp / 100));
        }
        if (0 < plan.disc || 0 < plan.discp) {
          $(this).append('<tr><td colspan="2"><small>Discount (' + plan.coupon + ')</small></td><td style="text-align:right"><small>-' + (
          discprice / 100).toFixed(2) + '</small></td></tr>');
        }
        if (0 < plan.tax && 0 < price) {
          $(this).append('<tr><td colspan="2"><small>CA Tax (' + plan.tax + '%)</small></td><td style="text-align:right"><small>+' + (
          taxed / 100).toFixed(2) + '</small></td></tr>');
        }
        if (plan.shipping) {
          $(this).append('<tr><td colspan="2"><small>Priority Shipping</small></td><td style="text-align:right"><small>+' + (
          plan.shipping / 100).toFixed(2) + '</small></td></tr>');
        }

      }).fadeIn(100);

      $('#signup_total_price span').fadeOut(100, function() {
        $(this).text('$' + (
        totals / 100).toFixed(2));
      }).fadeIn(100);
    }
  }
})();

module.exports = priceUpdate;
