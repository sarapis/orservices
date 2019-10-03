(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/alertify', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAlertify = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'alertify'; // import $ from 'jquery';

  var Alertify = function (_Plugin) {
    babelHelpers.inherits(Alertify, _Plugin);

    function Alertify() {
      babelHelpers.classCallCheck(this, Alertify);
      return babelHelpers.possibleConstructorReturn(this, (Alertify.__proto__ || Object.getPrototypeOf(Alertify)).apply(this, arguments));
    }

    babelHelpers.createClass(Alertify, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (this.options.labelOk) {
          this.options.okBtn = this.options.labelOk;
        }

        if (this.options.labelCancel) {
          this.options.cancelBtn = this.options.labelCancel;
        }

        this.$el.data('alertifyWrapApi', this);
      }
    }, {
      key: 'show',
      value: function show() {
        if (typeof alertify === 'undefined') return;
        var options = this.options;
        if (typeof options.delay !== 'undefined') {
          alertify.delay(options.delay);
        }

        if (typeof options.theme !== 'undefined') {
          alertify.theme(options.theme);
        }

        if (typeof options.cancelBtn !== 'undefined') {
          alertify.cancelBtn(options.cancelBtn);
        }

        if (typeof options.okBtn !== 'undefined') {
          alertify.okBtn(options.okBtn);
        }

        if (typeof options.placeholder !== 'undefined') {
          alertify.delay(options.placeholder);
        }

        if (typeof options.defaultValue !== 'undefined') {
          alertify.delay(options.defaultValue);
        }

        if (typeof options.maxLogItems !== 'undefined') {
          alertify.delay(options.maxLogItems);
        }

        if (typeof options.closeLogOnClick !== 'undefined') {
          alertify.delay(options.closeLogOnClick);
        }

        switch (options.type) {
          case 'confirm':
            alertify.confirm(options.confirmTitle, function () {
              alertify.success(options.successMessage);
            }, function () {
              alertify.error(options.errorMessage);
            });
            break;
          case 'prompt':
            alertify.prompt(options.promptTitle, function (str, ev) {
              var message = options.successMessage.replace('%s', str);
              alertify.success(message);
            }, function (ev) {
              alertify.error(options.errorMessage);
            });
            break;
          case 'log':
            alertify.log(options.logMessage);
            break;
          case 'success':
            alertify.success(options.successMessage);
            break;
          case 'error':
            alertify.error(options.errorMessage);
            break;
          default:
            alertify.alert(options.alertMessage);
            break;
        }
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          type: 'alert',
          delay: 5000,
          theme: 'bootstrap'
        };
      }
    }, {
      key: 'api',
      value: function api() {
        return 'click|show';
      }
    }]);
    return Alertify;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Alertify);

  exports.default = Alertify;
});
