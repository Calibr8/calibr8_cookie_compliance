/**
 * @file
 * Handle the CookieCompliance.
 */
(function ($, Drupal) {
  'use strict';

  /**
   * @namespace
   */
  Drupal.cookieCompliance = Drupal.cookieCompliance || {};

  const cookieIdentifier = 'cookie_compliance';

  /**
   * Drupal behavior.
   * Initialize Cookie compliance only once.
   */
  Drupal.behaviors.cookieCompliance = {
    attach: function (context) {
      $('body', context).once('CookieComplianceOnce').each(function () {
        var cookieStatus = Drupal.cookieCompliance.getCookieStatus();
        if (!cookieStatus) {
          Drupal.cookieCompliance.show();
        }
      });

      $('#calibr8-agree-cookie-compliance').click(function(e) {
        e.preventDefault();
        Drupal.cookieCompliance.setCookie(Drupal.settings.calibr8CookieCompliance.agree_value);
        Drupal.cookieCompliance.close();
      });

      $('#calibr8-disagree-cookie-compliance').click(function(e) {
        e.preventDefault();
        Drupal.cookieCompliance.setCookie(Drupal.settings.calibr8CookieCompliance.disagree_value);
        Drupal.cookieCompliance.close();
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
  Drupal.cookieCompliance.getCookieStatus = function() {
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
   * Set the cookie status
   */
  Drupal.cookieCompliance.setCookie = function (status) {
    var date = new Date();
    // Expire in +2year.
    date.setFullYear(date.getFullYear() + 2);

    var cookie = cookieIdentifier + '=' + status + ';expires=' + date.toUTCString() + ';path=/';
    document.cookie = cookie;
  };

  /**
   * Show the popup
   */
  Drupal.cookieCompliance.show = function() {
    $('.calibr8-cookie-compliance').slideDown();
  };

  Drupal.cookieCompliance.close = function() {
    $('.calibr8-cookie-compliance').slideUp();
  }

})(jQuery, Drupal);