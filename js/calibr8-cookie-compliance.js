/**
 * @file
 * Handle the calibr8CookieCompliance.
 */

(function ($, Drupal, drupalSettings) {
  'use strict';

  /**
   * @namespace
   */
  Drupal.calibr8CookieCompliance = Drupal.calibr8CookieCompliance || {};

  /**
   * Default settings and variables
   */
  Drupal.calibr8CookieCompliance.settings = {
    markup: '',
    cookie_expiration: 100
  };

  var cookieIdentifier = 'cookie_compliance';

  /**
   * Drupal behavior.
   * Initialize Cookie compliance only once.
   */
  Drupal.behaviors.calibr8CookieCompliance = {
    attach: function (context, drupalSettings) {
      Drupal.calibr8CookieCompliance.settings = $.extend(Drupal.calibr8CookieCompliance.settings, drupalSettings.calibr8_cookie_compliance);
      $('body', context).once('calibr8CookieComplianceOnce').each(function () {
        var cookieStatus = Drupal.calibr8CookieCompliance.getCookieStatus();
        if(!cookieStatus) {
          Drupal.calibr8CookieCompliance.show();
        }
      });

      $('#calibrate-agree-cookie-compliance').click(function(e) {
          e.preventDefault();
          Drupal.calibr8CookieCompliance.setCookie(1);
          Drupal.calibr8CookieCompliance.close();
      });
      $('#calibrate-disagree-cookie-compliance').click(function(e) {
          e.preventDefault();
          Drupal.calibr8CookieCompliance.setCookie(0);
          Drupal.calibr8CookieCompliance.close();
      });
    }
  };

  /**
   * Get the cookie status
   * @return:
   * - 0: cookie is not found or consent is not given.
   * - 1: cookie is found and consent is given
   */
  Drupal.calibr8CookieCompliance.getCookieStatus = function () {
      var name = cookieIdentifier + '=';
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) === ' ') {
              c = c.substring(1);
          }
          if (c.indexOf(name) === 0) {
              return c.substring(name.length, c.length);
          }
      }
      return false;
  };

  /**
   * Set the cookie status.
   */
  Drupal.calibr8CookieCompliance.setCookie = function (status) {
    var date = new Date();
    date.setTime(date.getTime() + (Drupal.calibr8CookieCompliance.settings.cookie_expiration*1000*60*60*24));
    var cookie = cookieIdentifier + '=' + status + ';expires=' + date.toUTCString() + ';path=' + drupalSettings.path.baseUrl;
    document.cookie = cookie;
  };

  /**
   * Show the popup.
   */
  Drupal.calibr8CookieCompliance.show = function() {
    $(document.body).append(Drupal.calibr8CookieCompliance.settings.markup);
      $('#calibr8-cookie-compliance').slideDown();
  };

  /**
   * Hide the popup.
   */
  Drupal.calibr8CookieCompliance.close = function() {
    $('#calibr8-cookie-compliance').slideUp();
  };

})(jQuery, Drupal, drupalSettings);