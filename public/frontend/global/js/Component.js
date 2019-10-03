(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Component', ['exports', 'jquery', 'State'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('State'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.State);
    global.Component = mod.exports;
  }
})(this, function (exports, _jquery, _State) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _State2 = babelHelpers.interopRequireDefault(_State);

  if (typeof Object.assign === 'undefined') Object.assign = _jquery2.default.extend;

  var _class = function () {
    function _class() {
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      babelHelpers.classCallCheck(this, _class);

      this.$el = options.$el ? options.$el : (0, _jquery2.default)(document);
      this.el = this.$el[0];
      delete options.$el;

      this.children = this.getDefaultChildren();
      this.actions = this.getDefaultActions();
      this.initialState = this.getDefaultState();

      this._willProcess = _jquery2.default.Callbacks();
      this._processed = _jquery2.default.Callbacks();

      if (this.willProcess) this._willProcess.add(this.willProcess);
      if (this.processed) this._processed.add(this.processed);

      this.isProcessed = false;

      this.mix(options);

      this.state = null;
    }

    babelHelpers.createClass(_class, [{
      key: '_combineInitialState',
      value: function _combineInitialState() {
        var childrenInitialState = {};
        for (var i = 0, l = this.children.length; i < l; i++) {
          var child = this.children[i];

          Object.assign(childrenInitialState, child.initialState);
        }
        return Object.assign(childrenInitialState, this.initialState);
      }
    }, {
      key: '_process',
      value: function _process(state) {
        this._willProcess.fireWith(this);

        this.state = state ? state : new _State2.default(this.initialState);

        this._registerActions();

        for (var i = 0, l = this.children.length; i < l; i++) {
          this.children[i]._process(this.state);
          this.children[i].isProcessed = true;
        }

        this._processed.fireWith(this);
      }
    }, {
      key: '_registerActions',
      value: function _registerActions() {
        var _this = this;

        var actions = this.actions;

        var _loop = function _loop(state) {
          var action = actions[state];
          if (typeof action === 'function') {
            _this.state.on(state, function () {
              var _actions$state;

              for (var _len = arguments.length, params = Array(_len), _key = 0; _key < _len; _key++) {
                params[_key] = arguments[_key];
              }

              (_actions$state = actions[state]).apply.apply(_actions$state, [_this].concat(params));
            });
          } else if (typeof action === 'string' && typeof _this[action] === 'function') {
            _this.state.on(state, function () {
              var _action;

              for (var _len2 = arguments.length, params = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
                params[_key2] = arguments[_key2];
              }

              (_action = _this[action]).apply.apply(_action, [_this].concat(params));
            });
          }
        };

        for (var state in actions) {
          _loop(state);
        }
      }
    }, {
      key: 'run',
      value: function run() {
        if (!this.isProcessed) {
          this._process();
          this.isProcessed = true;
        }

        this.setState.apply(this, arguments);
      }
    }, {
      key: 'setState',
      value: function setState() {
        if (this.state) {
          var _state;

          (_state = this.state).set.apply(_state, arguments);
        }
      }
    }, {
      key: 'getState',
      value: function getState() {
        if (this.state) {
          var _state2;

          return (_state2 = this.state).get.apply(_state2, arguments);
        } else {
          return null;
        }
      }
    }, {
      key: 'getDefaultState',
      value: function getDefaultState() {
        return {};
      }
    }, {
      key: 'getDefaultChildren',
      value: function getDefaultChildren() {
        return [];
      }
    }, {
      key: 'getDefaultActions',
      value: function getDefaultActions() {
        return {};
      }
    }, {
      key: 'mix',
      value: function mix() {
        var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

        if (this.isInit) return;

        var _options$children = options.children,
            children = _options$children === undefined ? [] : _options$children,
            _options$actions = options.actions,
            actions = _options$actions === undefined ? {} : _options$actions,
            _options$state = options.state,
            state = _options$state === undefined ? {} : _options$state,
            _options$willProcess = options.willProcess,
            willProcess = _options$willProcess === undefined ? false : _options$willProcess,
            _options$processed = options.processed,
            processed = _options$processed === undefined ? false : _options$processed;


        children = children.filter(function (child) {
          return child instanceof Component;
        });
        this.children = [].concat(babelHelpers.toConsumableArray(this.children), babelHelpers.toConsumableArray(children));

        this.actions = Object.assign({}, this.actions, actions);

        this.initialState = Object.assign({}, this.initialState, state);
        this.initialState = this._combineInitialState();

        if (typeof willProcess !== 'function') this._willProcess.add(willProcess);
        if (typeof processed !== 'function') this._processed.add(processed);

        delete options.children;
        delete options.actions;
        delete options.state;
        delete options.willProcess;
        delete options.processed;

        Object.assign(this, options);
        return this;
      }
    }, {
      key: 'triggerResize',
      value: function triggerResize() {
        if (document.createEvent) {
          var ev = document.createEvent('Event');
          ev.initEvent('resize', true, true);
          window.dispatchEvent(ev);
        } else {
          element = document.documentElement;
          var event = document.createEventObject();
          element.fireEvent('onresize', event);
        }
      }
    }]);
    return _class;
  }();

  exports.default = _class;
});
