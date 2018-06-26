(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Contacts', ['exports', 'BaseApp'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('BaseApp'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.BaseApp);
    global.AppContacts = mod.exports;
  }
})(this, function (exports, _BaseApp2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppContacts = undefined;

  var _BaseApp3 = babelHelpers.interopRequireDefault(_BaseApp2);

  var AppContacts = function (_BaseApp) {
    babelHelpers.inherits(AppContacts, _BaseApp);

    function AppContacts() {
      babelHelpers.classCallCheck(this, AppContacts);
      return babelHelpers.possibleConstructorReturn(this, (AppContacts.__proto__ || Object.getPrototypeOf(AppContacts)).apply(this, arguments));
    }

    babelHelpers.createClass(AppContacts, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppContacts.prototype.__proto__ || Object.getPrototypeOf(AppContacts.prototype), 'processed', this).call(this);
        this.$actionBtn = $('.site-action');
        this.$actionToggleBtn = this.$actionBtn.find('.site-action-toggle');
        this.$addMainForm = $('#addUserForm').modal({
          show: false
        });
        this.$content = $('#contactsContent');

        this.setupActionBtn();
        this.bindListChecked();
        this.handlSlidePanelContent();
      }
    }, {
      key: 'getDefaultActions',
      value: function getDefaultActions() {
        return Object.assign(babelHelpers.get(AppContacts.prototype.__proto__ || Object.getPrototypeOf(AppContacts.prototype), 'getDefaultActions', this).call(this), {
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
        return Object.assign(babelHelpers.get(AppContacts.prototype.__proto__ || Object.getPrototypeOf(AppContacts.prototype), 'getDefaultState', this).call(this), {
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
    }, {
      key: 'handlSlidePanelContent',
      value: function handlSlidePanelContent() {
        var _this4 = this;

        $(document).on('click', '[data-toggle=edit]', function () {
          var $button = $(this),
              $panel = $button.parents('.slidePanel');
          var $form = $panel.find('.user-info');

          $button.toggleClass('active');
          $form.toggleClass('active');
        });

        $(document).on('change', '.user-info .form-group', function (e) {
          var $input = $(_this4).find('input'),
              $span = $(_this4).siblings('span');
          $span.html($input.val());
        });
      }
    }]);
    return AppContacts;
  }(_BaseApp3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppContacts();
    }

    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppContacts;
  exports.AppContacts = AppContacts;
  exports.run = run;
  exports.getInstance = getInstance;
});
