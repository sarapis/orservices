(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/matchheight', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginMatchheight = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'matchHeight';

  var MatchHeight = function (_Plugin) {
    babelHelpers.inherits(MatchHeight, _Plugin);

    function MatchHeight() {
      babelHelpers.classCallCheck(this, MatchHeight);
      return babelHelpers.possibleConstructorReturn(this, (MatchHeight.__proto__ || Object.getPrototypeOf(MatchHeight)).apply(this, arguments));
    }

    babelHelpers.createClass(MatchHeight, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof _jquery2.default.fn.matchHeight === 'undefined') {
          return;
        }

        var $el = this.$el,
            matchSelector = $el.data('matchSelector');

        if (matchSelector) {
          $el.find(matchSelector).matchHeight(this.options);
        } else {
          $el.children().matchHeight(this.options);
        }
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return MatchHeight;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, MatchHeight);

  exports.default = MatchHeight;
});
