(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/animsition', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginAnimsition = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'animsition';

  var Animsition = function (_Plugin) {
    babelHelpers.inherits(Animsition, _Plugin);

    function Animsition() {
      babelHelpers.classCallCheck(this, Animsition);
      return babelHelpers.possibleConstructorReturn(this, (Animsition.__proto__ || Object.getPrototypeOf(Animsition)).apply(this, arguments));
    }

    babelHelpers.createClass(Animsition, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render(callback) {
        var options = this.options;

        if (options.random) {
          var li = options.inDefaults.length,
              lo = options.outDefaults.length;

          var ni = parseInt(li * Math.random(), 0),
              no = parseInt(lo * Math.random(), 0);

          options.inClass = options.inDefaults[ni];
          options.outClass = options.outDefaults[no];
        }

        this.$el.animsition(options);

        (0, _jquery2.default)('.' + options.loadingClass).addClass('loader-' + options.loadingType);

        if (this.$el.animsition('supportCheck', options)) {
          if (_jquery2.default.isFunction(callback)) {
            this.$el.one('animsition.end', function () {
              callback.call();
            });
          }

          return true;
        }
        if (_jquery2.default.isFunction(callback)) {
          callback.call();
        }
        return false;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          inClass: 'fade-in',
          outClass: 'fade-out',
          inDuration: 800,
          outDuration: 500,
          linkElement: '.animsition-link',
          loading: true,
          loadingParentElement: 'body',
          loadingClass: 'loader',
          loadingType: 'default',
          timeout: false,
          timeoutCountdown: 5000,
          onLoadEvent: true,
          browser: ['animation-duration', '-webkit-animation-duration'],
          overlay: false,
          // random: true,
          overlayClass: 'animsition-overlay-slide',
          overlayParentElement: 'body',

          inDefaults: ['fade-in', 'fade-in-up-sm', 'fade-in-up', 'fade-in-up-lg', 'fade-in-down-sm', 'fade-in-down', 'fade-in-down-lg', 'fade-in-left-sm', 'fade-in-left', 'fade-in-left-lg', 'fade-in-right-sm', 'fade-in-right', 'fade-in-right-lg',
          // 'overlay-slide-in-top', 'overlay-slide-in-bottom', 'overlay-slide-in-left', 'overlay-slide-in-right',
          'zoom-in-sm', 'zoom-in', 'zoom-in-lg'],
          outDefaults: ['fade-out', 'fade-out-up-sm', 'fade-out-up', 'fade-out-up-lg', 'fade-out-down-sm', 'fade-out-down', 'fade-out-down-lg', 'fade-out-left-sm', 'fade-out-left', 'fade-out-left-lg', 'fade-out-right-sm', 'fade-out-right', 'fade-out-right-lg',
          // 'overlay-slide-out-top', 'overlay-slide-out-bottom', 'overlay-slide-out-left', 'overlay-slide-out-right'
          'zoom-out-sm', 'zoom-out', 'zoom-out-lg']
        };
      }
    }]);
    return Animsition;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Animsition);

  exports.default = Animsition;
});
