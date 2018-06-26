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

    // Example Default
    // ---------------
    tableDragger.default(document.querySelector("#default-table"));

    // Example Sort Rows
    // -----------------
    tableDragger.default(document.querySelector("#row-table"), {
      mode: "row"
    });

    // Example Only Body
    // -----------------
    tableDragger.default(document.querySelector("#only-body-table"), {
      mode: "row",
      onlyBody: true
    });

    // Example Handler
    // ---------------
    tableDragger.default(document.querySelector("#handle-table"), {
      dragHandler: ".table-dragger-handle"
    });

    // Example Free
    // ------------
    tableDragger.default(document.querySelector("#free-table"), {
      mode: "row",
      onlyBody: true,
      dragHandler: ".table-dragger-handle"
    });

    // Example Event
    // -------------
    tableDragger.default(document.querySelector('#event-table'), {
        mode: 'free',
        dragHandler: '.table-dragger-handle',
        onlyBody: true
      })
      .on('drag', function() {
        console.log('drag');
      })
      .on('drop', function(from, to, el, mode) {
        console.log('drop ' + el.nodeName + ' from ' + from + ' ' + mode + ' to ' + to + ' ' + mode);
      })
      .on('shadowMove', function(from, to, el, mode) {
        console.log('move ' + el.nodeName + ' from ' + from + ' ' + mode + ' to ' + to + ' ' + mode);
      })
      .on('out', function(el, mode) {
        console.log('move out or drop ' + el.nodeName + ' in mode ' + mode);
      });
  });

})(document, window, jQuery);
