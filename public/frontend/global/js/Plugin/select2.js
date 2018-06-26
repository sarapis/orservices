(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/select2', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginSelect2 = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'select2'; // import $ from 'jquery';

  var Select2 = function (_Plugin) {
    babelHelpers.inherits(Select2, _Plugin);

    function Select2() {
      babelHelpers.classCallCheck(this, Select2);
      return babelHelpers.possibleConstructorReturn(this, (Select2.__proto__ || Object.getPrototypeOf(Select2)).apply(this, arguments));
    }

    babelHelpers.createClass(Select2, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          width: 'style'
        };
      }
    }]);
    return Select2;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Select2);

  exports.default = Select2;
});
