/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2017 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */
(function(document, window, $) {
  'use strict';

  var Site = window.Site;

  $(document).ready(function($) {
    Site.run();

    Waves.attach('.dropdown-menu:not([class*="dropdown-menu-"]) .dropdown-item', ['waves-classic']);
    Waves.attach('[class*="dropdown-menu-"]:not(.dropdown-menu-right):not(.dropdown-menu-left) .dropdown-item', ['waves-light']);
    Waves.attach('.dropdown-menu-right .dropdown-item', ['waves-classic']);
    Waves.attach('.dropdown-menu-left .dropdown-item', ['waves-classic']);
  });

  $(".example-dropdown-js select").dropdown();

})(document, window, jQuery);
