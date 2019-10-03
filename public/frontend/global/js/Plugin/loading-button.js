(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/loading-button', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginLoadingButton = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'loadingButton'; // import $ from 'jquery';

  var LoadingButton = function (_Plugin) {
    babelHelpers.inherits(LoadingButton, _Plugin);

    function LoadingButton() {
      babelHelpers.classCallCheck(this, LoadingButton);
      return babelHelpers.possibleConstructorReturn(this, (LoadingButton.__proto__ || Object.getPrototypeOf(LoadingButton)).apply(this, arguments));
    }

    babelHelpers.createClass(LoadingButton, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.text = this.$el.text();
        this.$el.data('loadingButtonApi', this);
      }
    }, {
      key: 'loading',
      value: function loading() {
        var $el = this.$el,
            i = this.options.time,
            loadingText = this.options.loadingText,
            opacity = this.options.opacity;

        $el.text(loadingText + '(' + i + ')').css('opacity', opacity);

        var timeout = setInterval(function () {
          $el.text(loadingText + '(' + --i + ')');
          if (i === 0) {
            clearInterval(timeout);
            $el.text(text).css('opacity', '1');
          }
        }, 1000);
      }
    }], [{
      key: 'api',
      value: function api() {
        return 'click|loading';
      }
    }, {
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          loadingText: 'Loading',
          time: 20,
          opacity: '0.6'
        };
      }
    }]);
    return LoadingButton;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, LoadingButton);

  exports.default = LoadingButton;
});
