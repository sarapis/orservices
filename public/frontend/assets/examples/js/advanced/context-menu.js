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

  // Demo 1
  // ------
  (function() {
    $.contextMenu({
      selector: '#simpleContextMenu',
      // callback: function(key, options) {
      //   var m = "clicked: " + key;
      //   window.console && console.log(m) || alert(m);
      // },
      items: {
        "edit": {
          name: "Edit",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-edit';
          }
        },
        "cut": {
          name: "Cut",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-scissors';
          }
        },
        "copy": {
          name: "Copy",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-copy';
          }
        },
        "paste": {
          name: "Paste",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-collection-item';
          }
        },
        "delete": {
          name: "Delete",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-delete';
          }
        },
        "sep1": "---------",
        "share": {
          name: "Share",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-share';
          }
        }
      }
    });
  })();

  // Demo 2
  // ------
  (function() {
    $.contextMenu({
      selector: '.contextMenu-example2 > span',
      // callback: function(key, options) {
      //   var m = "clicked: " + key;
      //   window.console && console.log(m) || alert(m);
      // },
      items: {
        "edit": {
          name: "Edit",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-edit';
          }
        },
        "cut": {
          name: "Cut",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-scissors';
          }
        },
        "copy": {
          name: "Copy",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-copy';
          }
        },
        "paste": {
          name: "Paste",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-collection-item';
          }
        },
        "delete": {
          name: "Delete",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-delete';
          }
        },
        "sep1": "---------",
        "share": {
          name: "Share",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-share';
          }
        }
      }
    });
  })();

  // Demo 3
  // ------
  (function() {
    $.contextMenu({
      selector: '.contextMenu-example3',
      callback: function(key, options) {
        var m = "clicked: " + key;
        window.console && console.log(m) || alert(m);
      },
      items: {
        "edit": {
          name: "Edit",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-edit';
          }
        },
        "cut": {
          name: "Cut",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-scissors';
          }
        },
        "copy": {
          name: "Copy",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-copy';
          }
        },
        "paste": {
          name: "Paste",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-collection-item';
          }
        },
        "delete": {
          name: "Delete",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-delete';
          }
        },
        "sep1": "---------",
        "share": {
          name: "Share",
          icon: function() {
            return 'context-menu-icon context-menu-extend-icon md-share';
          }
        }
      }
    });
  })();
})(document, window, jQuery);
