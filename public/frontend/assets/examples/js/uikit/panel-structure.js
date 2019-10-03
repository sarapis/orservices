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
  });

  // Example Button Random
  // ---------------------
  (function() {
    $('#exampleButtonRandom').on('click', function(e) {
      e.preventDefault();

      $('[data-plugin="progress"]').each(function() {
        var number = Math.round(Math.random(1) * 100) + '%';
        $(this).asProgress('go', number);
      });
    });
  })();

  // Example Panel With Tool
  // -----------------------
  window.customRefreshCallback = function(done) {
    var $panel = $(this);
    setTimeout(function() {
      done();
      $panel.find('.panel-body').html('Lorem ipsum In nostrud Excepteur velit reprehenderit quis consequat veniam officia nisi labore in est.');
    }, 1000);
  };

  // Example rating
  // ----------------------
  // data-plugin="rating" data-half="true" data-number="9" data-score="3" data-hints="bad,,,,regular,,,,gorgeous"
  $(".yellow-rating").raty({
    targetKeep: true,
    half: true,
    number: 9,
    score: 3,
    hints: ["bad", "", "", "", "regular", "", "", "", "gorgeous"],
    icon: "font",
    starType: "i",
    starOff: "icon wb-star",
    starOn: "icon wb-star yellow-600",
    cancelOff: "icon wb-minus-circle",
    cancelOn: "icon wb-minus-circle yellow-600",
    starHalf: "icon wb-star-half yellow-500"
  })
})(document, window, jQuery);
