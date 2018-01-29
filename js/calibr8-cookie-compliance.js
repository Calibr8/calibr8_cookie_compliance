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
    cookie_expiration: 100,
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
    }
  };

  /**
   * Get the cookie status
   * @return:
   * - 0: cookie is not found
   * - 1: cookie is found
   */
  Drupal.calibr8CookieCompliance.getCookieStatus = function () {
    var search = cookieIdentifier + '=';
    var returnValue = 0;
    if (document.cookie.length > 0) {
      var offset = document.cookie.indexOf(search);
      if (offset !== -1) {
        returnValue = 1;
      }
    }
    return returnValue;
  };

  /**
   * Set the cookie status
   */
  Drupal.calibr8CookieCompliance.setCookie = function (status) {
    var date = new Date();
    date.setDate(date.getDate() + Drupal.calibr8CookieCompliance.settings.cookie_expiration);
    var cookie = cookieIdentifier + '=' + status + ';expires=' + date.toUTCString() + ';path=' + drupalSettings.path.baseUrl;
    document.cookie = cookie;
  };

  /**
   * Show the popup
   */
  Drupal.calibr8CookieCompliance.show = function() {
    var $insert = $('#site-wrapper');
    if($('#calibr8-cookie-compliance-placeholder').length) {
      $insert = $('#calibr8-cookie-compliance-placeholder');
    }
    $insert.prepend(Drupal.calibr8CookieCompliance.settings.markup);
    $('#allow-cookies-link').bind('click', function(e) {
      e.preventDefault();
      Drupal.calibr8CookieCompliance.setCookie(1);
      $('#calibr8-cookie-compliance').slideUp(function() {
        // trigger resize event.
        $(window).resize();
      });
    });
  };

})(jQuery, Drupal, drupalSettings);