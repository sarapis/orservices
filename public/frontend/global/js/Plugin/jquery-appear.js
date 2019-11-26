(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jquery-appear', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginJqueryAppear = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'appear';

  var Appear = function (_Plugin) {
    babelHelpers.inherits(Appear, _Plugin);

    function Appear() {
      babelHelpers.classCallCheck(this, Appear);
      return babelHelpers.possibleConstructorReturn(this, (Appear.__proto__ || Object.getPrototypeOf(Appear)).apply(this, arguments));
    }

    babelHelpers.createClass(Appear, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'bind',
      value: function bind() {
        var _this2 = this;

        this.$el.on('appear', function () {
          if (_this2.$el.hasClass('appear-no-repeat')) {
            return;
          }
          _this2.$el.removeClass('invisible').addClass('animation-' + _this2.options.animate);

          if (_this2.$el.data('repeat') === false) {
            _this2.$el.addClass('appear-no-repeat');
          }
        });

        (0, _jquery2.default)(document).on('disappear', function () {
          if (_this2.$el.hasClass('appear-no-repeat')) {
            return;
          }

          _this2.$el.addClass('invisible').removeClass('animation-' + _this2.options.animate);
        });
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.appear) {
          return;
        }

        this.$el.appear(this.options);
        this.$el.not(':appeared').addClass('invisible');

        this.bind();
      }
    }]);
    return Appear;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Appear);
  exports.default = Appear;
});
