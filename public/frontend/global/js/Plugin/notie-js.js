(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/notie-js', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginNotieJs = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'notie'; // import $ from 'jquery';

  var Notie = function (_Plugin) {
    babelHelpers.inherits(Notie, _Plugin);

    function Notie() {
      babelHelpers.classCallCheck(this, Notie);
      return babelHelpers.possibleConstructorReturn(this, (Notie.__proto__ || Object.getPrototypeOf(Notie)).apply(this, arguments));
    }

    babelHelpers.createClass(Notie, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.$el.data('notieApi', this);
      }
    }, {
      key: 'show',
      value: function show() {
        var options = this.options;

        if (options.type !== undefined) {
          if (options.type === 'confirm') {
            notie.confirm(options.text, options.yesText, options.noText, function () {
              notie.alert(1, options.yesMsg, 1.5);
            }, function () {
              notie.alert(3, options.noMsg, 1.5);
            });
          }

          if (options.type === 'input') {
            notie.input({
              type: options.inputType,
              placeholder: options.placeholder,
              prefilledValue: options.prefilledvalue
            }, options.text, options.yesText, options.noText, function (valueInput) {
              notie.alert(1, 'you entered: ' + valueInput, 1.5);
            }, function (valueInput) {
              notie.alert(3, 'You cancelled with this value: ' + valueInput, 2);
            });
          }

          if (options.type === 'success') {
            notie.alert(1, options.text, options.delay);
          }

          if (options.type === 'warning') {
            notie.alert(2, options.text, options.delay);
          }

          if (options.type === 'error') {
            notie.alert(3, options.text, options.delay);
          }

          if (options.type === 'info') {
            notie.alert(4, options.text, options.delay);
          }

          if (options.animationdelay !== undefined && typeof options.animationdelay === 'number') {
            notie.setOptions({
              animationDelay: options.animationDelay
            });
          }

          if (options.bgclickdismiss !== undefined && typeof options.bgclickdismiss === 'boolean') {
            notie.setOptions({
              backgroundClickDismiss: options.bgclickdismiss
            });
          }
        }
      }
    }], [{
      key: 'api',
      value: function api() {
        return 'click|show';
      }
    }]);
    return Notie;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Notie);

  exports.default = Notie;
});
