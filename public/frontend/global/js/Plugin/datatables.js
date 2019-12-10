(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/datatables', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginDatatables = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'dataTable';

  var DataTable = function (_Plugin) {
    babelHelpers.inherits(DataTable, _Plugin);

    function DataTable() {
      babelHelpers.classCallCheck(this, DataTable);
      return babelHelpers.possibleConstructorReturn(this, (DataTable.__proto__ || Object.getPrototypeOf(DataTable)).apply(this, arguments));
    }

    babelHelpers.createClass(DataTable, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.dataTable) {
          return;
        }

        // if ($.fn.dataTable.TableTools) {
        //   // Set the classes that TableTools uses to something suitable for Bootstrap
        //   $.extend(true, $.fn.dataTable.TableTools.classes, {
        //     container: 'DTTT btn-group btn-group float-left',
        //     buttons: {
        //       normal: 'btn btn-outline btn-default',
        //       disabled: 'disabled'
        //     },
        //     print: {
        //       body: 'site-print DTTT_Print'
        //     }
        //   });
        // }

        this.$el.dataTable(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          responsive: true,
          language: {
            sSearchPlaceholder: 'Search..',
            lengthMenu: '_MENU_',
            search: '_INPUT_'
            // paginate: {
            //   previous: '<i class="icon md-chevron-left"></i>',
            //   next: '<i class="icon md-chevron-right"></i>'
            // }
          }
          // classes: {
          //   sFilterInput: "form-control form-control-sm",
          //   sLengthSelect: "form-control form-control-sm"
          // }
        };
      }
    }]);
    return DataTable;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, DataTable);

  exports.default = DataTable;
});
