(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/animate-list', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginAnimateList = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'animateList';

  var AnimateList = function (_Plugin) {
    babelHelpers.inherits(AnimateList, _Plugin);

    function AnimateList() {
      babelHelpers.classCallCheck(this, AnimateList);
      return babelHelpers.possibleConstructorReturn(this, (AnimateList.__proto__ || Object.getPrototypeOf(AnimateList)).apply(this, arguments));
    }

    babelHelpers.createClass(AnimateList, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        var $el = this.$el;

        var animatedBox = function () {
          function animatedBox($el, opts) {
            babelHelpers.classCallCheck(this, animatedBox);

            this.options = opts;
            this.$children = $el.find(opts.child);
            this.$children.addClass('animation-' + opts.animate);
            this.$children.css('animation-fill-mode', opts.fill);
            this.$children.css('animation-duration', opts.duration + 'ms');

            var delay = 0,
                self = this;

            this.$children.each(function () {
              (0, _jquery2.default)(this).css('animation-delay', delay + 'ms');
              delay += self.options.delay;
            });
          }

          babelHelpers.createClass(animatedBox, [{
            key: 'run',
            value: function run(type) {
              var _this2 = this;

              this.$children.removeClass('animation-' + this.options.animate);
              if (typeof type !== 'undefined') {
                this.options.animate = type;
              }
              setTimeout(function () {
                _this2.$children.addClass('animation-' + _this2.options.animate);
              }, 0);
            }
          }]);
          return animatedBox;
        }();

        $el.data('animateList', new animatedBox($el, this.options));
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          child: '.panel',
          duration: 250,
          delay: 50,
          animate: 'scale-up',
          fill: 'backwards'
        };
      }
    }]);
    return AnimateList;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, AnimateList);

  exports.default = AnimateList;
});
