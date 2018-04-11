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
          Drupal.calibr8CookieCompliance.setCookie(Drupal.calibr8CookieCompliance.settings.cookie_agree_value);
          Drupal.calibr8CookieCompliance.close();
      });

      $('#calibrate-disagree-cookie-compliance').click(function(e) {
          e.preventDefault();
          Drupal.calibr8CookieCompliance.setCookie(Drupal.calibr8CookieCompliance.settings.cookie_disagree_value);
          Drupal.calibr8CookieCompliance.close();
      });
    }
  };

  /**
   * Cookie status block.
   */
  Drupal.behaviors.cookieStatus = {
    attach: function (context) {
      var $cookieStatus = $('.calibr8-cookie-compliance-status');
      if ($cookieStatus.length > 0) {
        var $text = $('.calibr8-cookie-compliance-status__text');
        $text.html($text.html().replace('[[status]]', '<span id="calibr8-cookie-status-wrapper"></span>'));

        var cookieStatus = Drupal.cookieCompliance.getCookieStatus();
        Drupal.behaviors.cookieStatus.setStatusText(cookieStatus);

        Drupal.behaviors.cookieStatus.toggleButtonHandler();

        $cookieStatus.show();
      }
    }
  };

  Drupal.behaviors.cookieStatus.toggleButtonHandler = function() {
    var $toggleButton = $('#calibr8-cookie-status-toggle');
    $toggleButton.click(function(e) {
      e.preventDefault();

      var cookieStatus = Drupal.cookieCompliance.getCookieStatus();
      if (cookieStatus === Drupal.settings.calibr8CookieCompliance.agree_value) {
        Drupal.cookieCompliance.setCookie(Drupal.settings.calibr8CookieCompliance.disagree_value);
        Drupal.behaviors.cookieStatus.setStatusText(Drupal.settings.calibr8CookieCompliance.disagree_value);
      } else {
        Drupal.cookieCompliance.setCookie(Drupal.settings.calibr8CookieCompliance.agree_value);
        Drupal.behaviors.cookieStatus.setStatusText(Drupal.settings.calibr8CookieCompliance.agree_value);
      }
    });
  };

  Drupal.behaviors.cookieStatus.setStatusText = function(cookieStatus) {
    var $toggleButton = $('#calibr8-cookie-status-toggle');
    var $textWrapper = $('.calibr8-cookie-compliance-status__text');
    var $text = $('#calibr8-cookie-status-wrapper');
    var text = '';
    var linkText = '';

    if (cookieStatus === Drupal.settings.calibr8CookieCompliance.agree_value) {
      linkText = $toggleButton.attr('data-disagree');
      text = $textWrapper.attr('data-agree');
    }
    else {
      linkText = $toggleButton.attr('data-agree');
      text = $textWrapper.attr('data-disagree');
    }

    $toggleButton.html(linkText);
    $text.html(text);
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