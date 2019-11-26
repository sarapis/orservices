(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/more-button', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginMoreButton = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'moreButton';

  var MoreButton = function (_Plugin) {
    babelHelpers.inherits(MoreButton, _Plugin);

    function MoreButton() {
      babelHelpers.classCallCheck(this, MoreButton);
      return babelHelpers.possibleConstructorReturn(this, (MoreButton.__proto__ || Object.getPrototypeOf(MoreButton)).apply(this, arguments));
    }

    babelHelpers.createClass(MoreButton, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.$target = (0, _jquery2.default)(this.options.more);
        this.$el.data('moreButtonApi', this);
      }
    }, {
      key: 'toggle',
      value: function toggle() {
        this.$target.toggle();
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          more: ''
        };
      }
    }, {
      key: 'api',
      value: function api() {
        return 'click|toggle';
      }
    }]);
    return MoreButton;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, MoreButton);

  exports.default = MoreButton;
});
