(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/multi-select', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginMultiSelect = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'multiSelect'; // import $ from 'jquery';

  var MultiSelect = function (_Plugin) {
    babelHelpers.inherits(MultiSelect, _Plugin);

    function MultiSelect() {
      babelHelpers.classCallCheck(this, MultiSelect);
      return babelHelpers.possibleConstructorReturn(this, (MultiSelect.__proto__ || Object.getPrototypeOf(MultiSelect)).apply(this, arguments));
    }

    babelHelpers.createClass(MultiSelect, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return MultiSelect;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, MultiSelect);

  exports.default = MultiSelect;
});
