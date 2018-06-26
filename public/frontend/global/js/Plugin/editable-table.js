(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/editable-table', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginEditableTable = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'editableTable';

  var EditableTable = function (_Plugin) {
    babelHelpers.inherits(EditableTable, _Plugin);

    function EditableTable() {
      babelHelpers.classCallCheck(this, EditableTable);
      return babelHelpers.possibleConstructorReturn(this, (EditableTable.__proto__ || Object.getPrototypeOf(EditableTable)).apply(this, arguments));
    }

    babelHelpers.createClass(EditableTable, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.editableTableWidget) {
          return;
        }

        var $el = this.$el;

        $el.editableTableWidget(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return EditableTable;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, EditableTable);

  exports.default = EditableTable;
});
