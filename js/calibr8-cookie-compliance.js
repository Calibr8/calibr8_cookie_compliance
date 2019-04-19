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
  Drupal.calibr8CookieInfoBlock = Drupal.calibr8CookieInfoBlock || {};
  Drupal.calibr8CookieStatusBlock = Drupal.calibr8CookieStatusBlock || {};
  Drupal.calibr8CookieDeleteBlock = Drupal.calibr8CookieDeleteBlock || {};

  /**
   * Default settings and variables
   */
  Drupal.calibr8CookieCompliance.settings = {
    markup: '',
    cookie_expiration: 100
  };

  var cookieIdentifier = 'cookie_compliance_' + drupalSettings.calibr8_cookie_compliance.site_id;

  /**
   * Get the cookie status
   * @return:
   * - 0: cookie is not found or error
   * - 1: cookie is found and consent is not given
   * - 2: cookie is found and consent is given
   */
  Drupal.calibr8CookieCompliance.getCookieStatus = function () {
    return Drupal.calibr8CookieCompliance.getCookieValue(cookieIdentifier + '=');
  };

  /**
   * Helper function to retrieve a value.
   *
   * @param name
   * @returns {*}
   */
  Drupal.calibr8CookieCompliance.getCookieValue = function (name) {
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
    return 0;
  };

  /**
   * Set the cookie status.
   */
  Drupal.calibr8CookieCompliance.setCookie = function (status) {
    var date = new Date();
    date.setTime(date.getTime() + (Drupal.calibr8CookieCompliance.settings.cookie_expiration*1000*60*60*24));

    var cookie = cookieIdentifier + '=' + status + ';expires='
        + date.toUTCString() + ';path='
        + Drupal.calibr8CookieCompliance.settings.cookie_path;
    document.cookie = cookie;
  };

  /**
   * Delete all cookies from this domain
   * @return {boolean}
   */
  Drupal.calibr8CookieCompliance.deleteAllCookies = function() {
    var error = false;
    try {
      var cookies = document.cookie.split("; ");
      for (var c = 0; c < cookies.length; c++) {
        var d = window.location.hostname.split(".");
        while (d.length > 0) {
          var cookieBase = encodeURIComponent(cookies[c].split(";")[0].split("=")[0]) + '=; expires=Thu, 01-Jan-1979 00:00:01 GMT; domain=' + d.join('.') + ' ;path=';
          var cookieBaseNoDomain = encodeURIComponent(cookies[c].split(";")[0].split("=")[0]) + '=; expires=Thu, 01-Jan-1979 00:00:01 GMT;path=';
          var p = location.pathname.split('/');
          document.cookie = cookieBase + '/';
          document.cookie = cookieBaseNoDomain + '/';
          while (p.length > 0) {
            document.cookie = cookieBase + p.join('/');
            document.cookie = cookieBaseNoDomain + p.join('/');
            p.pop();
          }
          d.shift();
        }
      }
    }
    catch (err) {
      error = true;
    }
    return error;
  };

  /**
   * Cookie info block (popup)
   */
  Drupal.behaviors.calibr8CookieComplianceInfoBlock = {
    attach: function (context, drupalSettings) {
      Drupal.calibr8CookieCompliance.settings = $.extend(Drupal.calibr8CookieCompliance.settings, drupalSettings.calibr8_cookie_compliance);

      $('body', context).once('calibr8CookieComplianceOnce').each(function () {
        var cookieStatus = Drupal.calibr8CookieCompliance.getCookieStatus();

        if (!cookieStatus) {
          Drupal.calibr8CookieInfoBlock.show();
        }
      });

      $('#calibr8-cookie-compliance-consent').once().click(function(e) {
        e.preventDefault();
        Drupal.calibr8CookieCompliance.setCookie(Drupal.calibr8CookieCompliance.settings.cookie_consent_value);
        Drupal.calibr8CookieInfoBlock.close();
      });
      $('#calibr8-cookie-compliance-noconsent').once().click(function(e) {
        e.preventDefault();
        Drupal.calibr8CookieCompliance.setCookie(Drupal.calibr8CookieCompliance.settings.cookie_noconsent_value);
        Drupal.calibr8CookieInfoBlock.close();
      });

    }
  };

  Drupal.calibr8CookieInfoBlock.show = function() {
    $('body').addClass('has-cookie-compliance');
    $(document.body).append(Drupal.calibr8CookieCompliance.settings.markup);
    $('#calibr8-cookie-compliance').slideDown();
  };

  Drupal.calibr8CookieInfoBlock.close = function() {
    $('body').removeClass('has-cookie-compliance');
    $('#calibr8-cookie-compliance').slideUp();
  };

  /**
   * Cookie status block.
   */
  Drupal.behaviors.calibr8CookieStatusBlock = {
    attach: function (context) {

      var cookieStatus = Drupal.calibr8CookieCompliance.getCookieStatus();
      Drupal.calibr8CookieStatusBlock.setStatusText(cookieStatus);

      $('#calibr8-cookie-compliance-status-toggle').once().click(function(e) {
        e.preventDefault();
        var cookieStatus = Drupal.calibr8CookieCompliance.getCookieStatus();
        if (parseInt(cookieStatus) === parseInt(Drupal.calibr8CookieCompliance.settings.cookie_consent_value)) {
          Drupal.calibr8CookieCompliance.setCookie(Drupal.calibr8CookieCompliance.settings.cookie_noconsent_value);
          Drupal.calibr8CookieStatusBlock.setStatusText(Drupal.calibr8CookieCompliance.settings.cookie_noconsent_value);
        } else {
          Drupal.calibr8CookieCompliance.setCookie(Drupal.calibr8CookieCompliance.settings.cookie_consent_value);
          Drupal.calibr8CookieStatusBlock.setStatusText(Drupal.calibr8CookieCompliance.settings.cookie_consent_value);
        }
      });
    }
  };

  Drupal.calibr8CookieStatusBlock.setStatusText = function(cookieStatus) {
    var $toggleButton = $('#calibr8-cookie-compliance-status-toggle');
    var $textWrapper = $('.calibr8-cookie-compliance-status__text');
    var $anchor = $('#calibr8-cookie-compliance-status-text-anchor');

    // get anchor and button text
    var text = '';
    var linkText = '';
    if (parseInt(cookieStatus) === parseInt(Drupal.calibr8CookieCompliance.settings.cookie_consent_value)) {
      linkText = $toggleButton.attr('data-noconsent');
      text = $textWrapper.attr('data-consent');
    }
    else {
      linkText = $toggleButton.attr('data-consent');
      text = $textWrapper.attr('data-noconsent');
    }
    // replace text
    $toggleButton.html(linkText);
    $anchor.html(text);
  };

  /**
   * Delete all cookies block
   */
  Drupal.behaviors.calibr8CookieDeleteBlock = {
    attach: function (context) {
      var $delete_button = $('#calibr8-cookie-compliance-delete');
      $delete_button.once().click(function(e) {
        e.preventDefault();
        var error = Drupal.calibr8CookieCompliance.deleteAllCookies();
        if (!error) {
          var successfulMessage= $(this).attr('data-delete-successful');
          $(this).closest('.calibr8-cookie-compliance-delete').append('<div class="calibr8-cookie-compliance-delete__success">' + successfulMessage + '</div>');
          $(this).closest('.calibr8-cookie-compliance-delete__button').remove();
        }
      });
    }
  };

})(jQuery, Drupal, drupalSettings);