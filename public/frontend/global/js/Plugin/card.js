(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/card', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginCard = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'card';

  var Card = function (_Plugin) {
    babelHelpers.inherits(Card, _Plugin);

    function Card() {
      babelHelpers.classCallCheck(this, Card);
      return babelHelpers.possibleConstructorReturn(this, (Card.__proto__ || Object.getPrototypeOf(Card)).apply(this, arguments));
    }

    babelHelpers.createClass(Card, [{
      key: 'getName',
      value: function getName() {
        return;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.card) {
          return;
        }

        var $el = this.$el,
            options = this.options;

        if (options.target) {
          options.container = (0, _jquery2.default)(options.target);
        }
        $el.card(options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Card;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Card);

  exports.default = Card;
});
