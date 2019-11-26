(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Message', ['exports', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Site);
    global.AppMessage = mod.exports;
  }
})(this, function (exports, _Site2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppMessage = undefined;

  var _Site3 = babelHelpers.interopRequireDefault(_Site2);

  var ChatsWrap = function () {
    function ChatsWrap($el) {
      var _this = this;

      babelHelpers.classCallCheck(this, ChatsWrap);

      this.$el = $el;
      this.$historyBtn = $('#historyBtn');
      this.scrollChatsToBottom();

      $(window).on('resize', function () {
        _this.scrollChatsToBottom();
      });
    }

    babelHelpers.createClass(ChatsWrap, [{
      key: 'scrollChatsToBottom',
      value: function scrollChatsToBottom() {
        var $el = this.$el;
        var chatsWrapH = $el.height();
        var chatsH = $('.chats', $el).outerHeight();
        var historyBtnH = this.$historyBtn.outerHeight();

        $el.scrollTop(chatsH + historyBtnH - chatsWrapH);
      }
    }]);
    return ChatsWrap;
  }();

  var AppMessage = function (_Site) {
    babelHelpers.inherits(AppMessage, _Site);

    function AppMessage() {
      babelHelpers.classCallCheck(this, AppMessage);
      return babelHelpers.possibleConstructorReturn(this, (AppMessage.__proto__ || Object.getPrototypeOf(AppMessage)).apply(this, arguments));
    }

    babelHelpers.createClass(AppMessage, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppMessage.prototype.__proto__ || Object.getPrototypeOf(AppMessage.prototype), 'processed', this).call(this);

        this.newChatLists = [];
        this.$chatsWrap = $('.app-message-chats');
        this.chatApi = new ChatsWrap(this.$chatsWrap);

        this.$textArea = $('.message-input textarea');
        this.$textareaWrap = $('.app-message-input');

        this.$msgEdit = $('.message-input>.form-control');
        this.$sendBtn = $('.message-input-btn');

        this.steupMessage();
        this.setupTextarea();
      }
    }, {
      key: 'getDefaultState',
      value: function getDefaultState() {
        return Object.assign(babelHelpers.get(AppMessage.prototype.__proto__ || Object.getPrototypeOf(AppMessage.prototype), 'getDefaultState', this).call(this), {
          chatListsLength: 0
        });
      }
    }, {
      key: 'getDefaultActions',
      value: function getDefaultActions() {
        return Object.assign(babelHelpers.get(AppMessage.prototype.__proto__ || Object.getPrototypeOf(AppMessage.prototype), 'getDefaultActions', this).call(this), {
          chatListsLength: function chatListsLength(length) {
            if (this.newChatLists[length - 1]) {
              var $newMsg = $('<div class=\'chat-content\'><p>' + this.newChatLists[length - 1] + '</p></div>');

              $('.chat').last().find('.chat-body').append($newMsg);
              this.$msgEdit.attr('placeholder', '');
              this.$msgEdit.val('');
            } else {
              this.$msgEdit.attr('placeholder', 'type text here...');
            }

            this.chatApi.scrollChatsToBottom();
          }
        });
      }
    }, {
      key: 'setupTextarea',
      value: function setupTextarea() {
        var _this3 = this;

        autosize($('.message-input textarea'));

        this.$textArea.on('autosize:resized', function () {
          _this3.$chatsWrap.css('height', 'calc(100% - ' + _this3.$textareaWrap.outerHeight() + 'px)');
          _this3.triggerResize();
        });
      }
    }, {
      key: 'steupMessage',
      value: function steupMessage() {
        var _this4 = this;

        this.$sendBtn.on('click', function () {
          var num = _this4.getState('chatListsLength');
          _this4.newChatLists.push(_this4.getMsg());
          _this4.setState('chatListsLength', ++num);
        });
      }
    }, {
      key: 'getMsg',
      value: function getMsg() {
        return this.$msgEdit.val();
      }
    }]);
    return AppMessage;
  }(_Site3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppMessage();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppMessage;
  exports.AppMessage = AppMessage;
  exports.run = run;
  exports.getInstance = getInstance;
});
