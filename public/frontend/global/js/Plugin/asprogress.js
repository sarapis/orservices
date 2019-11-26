(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/asprogress', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginAsprogress = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'progress';

  var Progress = function (_Plugin) {
    babelHelpers.inherits(Progress, _Plugin);

    function Progress() {
      babelHelpers.classCallCheck(this, Progress);
      return babelHelpers.possibleConstructorReturn(this, (Progress.__proto__ || Object.getPrototypeOf(Progress)).apply(this, arguments));
    }

    babelHelpers.createClass(Progress, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.asProgress) {
          return;
        }

        var $el = this.$el;

        $el.asProgress(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          bootstrap: true,

          onUpdate: function onUpdate(n) {
            var per = (n - this.min) / (this.max - this.min);
            if (per < 0.5) {
              this.$target.addClass('progress-bar-success').removeClass('progress-bar-warning progress-bar-danger');
            } else if (per >= 0.5 && per < 0.8) {
              this.$target.addClass('progress-bar-warning').removeClass('progress-bar-success progress-bar-danger');
            } else {
              this.$target.addClass('progress-bar-danger').removeClass('progress-bar-success progress-bar-warning');
            }
          },
          labelCallback: function labelCallback(n) {
            var label = void 0;
            var labelType = this.$element.data('labeltype');

            if (labelType === 'percentage') {
              var percentage = this.getPercentage(n);
              label = percentage + '%';
            } else if (labelType === 'steps') {
              var total = this.$element.data('totalsteps');
              if (!total) {
                total = 10;
              }
              var step = Math.round(total * (n - this.min) / (this.max - this.min));
              label = step + ' / ' + total;
            } else {
              label = n;
            }

            if (this.$element.parent().hasClass('contextual-progress')) {
              this.$element.parent().find('.progress-label').html(label);
            }

            return label;
          }
        };
      }
    }]);
    return Progress;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Progress);

  exports.default = Progress;
});
