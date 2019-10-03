(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Mailbox', ['exports', 'BaseApp'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('BaseApp'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.BaseApp);
    global.AppMailbox = mod.exports;
  }
})(this, function (exports, _BaseApp2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppMailbox = undefined;

  var _BaseApp3 = babelHelpers.interopRequireDefault(_BaseApp2);

  var AppMailbox = function (_BaseApp) {
    babelHelpers.inherits(AppMailbox, _BaseApp);

    function AppMailbox() {
      babelHelpers.classCallCheck(this, AppMailbox);
      return babelHelpers.possibleConstructorReturn(this, (AppMailbox.__proto__ || Object.getPrototypeOf(AppMailbox)).apply(this, arguments));
    }

    babelHelpers.createClass(AppMailbox, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppMailbox.prototype.__proto__ || Object.getPrototypeOf(AppMailbox.prototype), 'processed', this).call(this);

        this.$actionBtn = $('.site-action');
        this.$actionToggleBtn = this.$actionBtn.find('.site-action-toggle');
        this.$addMainForm = $('#addMailForm').modal({
          show: false
        });
        this.$content = $('#mailContent');

        this.setupActionBtn();
        this.bindListChecked();
      }
    }, {
      key: 'getDefaultActions',
      value: function getDefaultActions() {
        return Object.assign(babelHelpers.get(AppMailbox.prototype.__proto__ || Object.getPrototypeOf(AppMailbox.prototype), 'getDefaultActions', this).call(this), {
          listChecked: function listChecked(checked) {
            var api = this.$actionBtn.data('actionBtn');
            if (checked) {
              api.show();
            } else {
              api.hide();
            }
          }
        });
      }
    }, {
      key: 'getDefaultState',
      value: function getDefaultState() {
        return Object.assign(babelHelpers.get(AppMailbox.prototype.__proto__ || Object.getPrototypeOf(AppMailbox.prototype), 'getDefaultState', this).call(this), {
          listChecked: false
        });
      }
    }, {
      key: 'setupActionBtn',
      value: function setupActionBtn() {
        var _this2 = this;

        this.$actionToggleBtn.on('click', function (e) {
          if (!_this2.getState('listChecked')) {
            _this2.$addMainForm.modal('show');
            e.stopPropagation();
          }
        });
      }
    }, {
      key: 'bindListChecked',
      value: function bindListChecked() {
        var _this3 = this;

        this.$content.on('asSelectable::change', function (e, api, checked) {
          _this3.setState('listChecked', checked);
        });
      }
    }]);
    return AppMailbox;
  }(_BaseApp3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppMailbox();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppMailbox;
  exports.AppMailbox = AppMailbox;
  exports.run = run;
  exports.getInstance = getInstance;
});
