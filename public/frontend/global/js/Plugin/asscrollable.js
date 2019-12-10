(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/asscrollable', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAsscrollable = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'scrollable'; // import $ from 'jquery';

  var Scrollable = function (_Plugin) {
    babelHelpers.inherits(Scrollable, _Plugin);

    function Scrollable() {
      babelHelpers.classCallCheck(this, Scrollable);
      return babelHelpers.possibleConstructorReturn(this, (Scrollable.__proto__ || Object.getPrototypeOf(Scrollable)).apply(this, arguments));
    }

    babelHelpers.createClass(Scrollable, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        var $el = this.$el;

        $el.asScrollable(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          namespace: 'scrollable',
          contentSelector: '> [data-role=\'content\']',
          containerSelector: '> [data-role=\'container\']'
        };
      }
    }]);
    return Scrollable;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Scrollable);

  exports.default = Scrollable;
});
