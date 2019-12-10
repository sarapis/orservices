(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jquery-wizard', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginJqueryWizard = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'wizard'; // import $ from 'jquery';

  var Wizard = function (_Plugin) {
    babelHelpers.inherits(Wizard, _Plugin);

    function Wizard() {
      babelHelpers.classCallCheck(this, Wizard);
      return babelHelpers.possibleConstructorReturn(this, (Wizard.__proto__ || Object.getPrototypeOf(Wizard)).apply(this, arguments));
    }

    babelHelpers.createClass(Wizard, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          step: '.steps .step, .pearls .pearl',
          templates: {
            buttons: function buttons() {
              var options = this.options;
              return '<div class="wizard-buttons"><a class="btn btn-default btn-outline" href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a><a class="btn btn-primary btn-outline float-right" href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a><a class="btn btn-success btn-outline float-right" href="#' + this.id + '" data-wizard="finish" role="button">' + options.buttonLabels.finish + '</a></div>';
            }
          },
          classes: {
            step: {
              active: 'active'
            },
            button: {
              hide: 'hidden-xs-up',
              disabled: 'disabled'
            }
          }
        };
      }
    }]);
    return Wizard;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Wizard);

  exports.default = Wizard;
});
