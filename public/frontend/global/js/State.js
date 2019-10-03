(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/State', ['exports', 'jquery'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery);
    global.State = mod.exports;
  }
})(this, function (exports, _jquery) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _class = function () {
    function _class(states) {
      babelHelpers.classCallCheck(this, _class);

      this._states = Object.assign({}, states);
      this._values = {};
      this._relations = {};
      this._callbacks = {};
      this._define();
    }

    babelHelpers.createClass(_class, [{
      key: '_define',
      value: function _define() {
        var _this = this;

        var self = this,
            keys = Object.keys(this._states),
            obj = {},
            tmpRelations = [],
            composites = [];

        var _loop = function _loop(i, l) {
          var key = keys[i],
              value = _this._states[key];
          if (typeof value !== 'function') {
            Object.defineProperty(obj, key, {
              set: function set() {
                return false;
              },
              get: function get() {
                tmpRelations.push(key);
                return self._states[key];
              },

              enumerable: true,
              configurable: true
            });
            _this._values[key] = _this._states[key];
            _this._relations[key] = [];
          } else {
            composites.push(key);
          }
        };

        for (var i = 0, l = keys.length; i < l; i++) {
          _loop(i, l);
        }

        var _loop2 = function _loop2(i, l) {
          var key = composites[i];
          Object.defineProperty(obj, key, {
            set: function set() {
              return false;
            },
            get: function get() {
              var value = self._states[key].call(obj);
              self._addRelation(key, tmpRelations);
              tmpRelations = [];
              self._values[key] = value;
              return value;
            },

            enumerable: true,
            configurable: true
          });

          // use get function to create the relationship
          obj[key];
        };

        for (var i = 0, l = composites.length; i < l; i++) {
          _loop2(i, l);
        }
      }
    }, {
      key: '_compare',
      value: function _compare(state) {
        if (this._states[state] !== this._values[state]) {
          var value = this._values[state];
          this._values[state] = this._states[state];
          this._dispatch(state, value, this._states[state]);
          this._compareComposite(state);
        }
      }
    }, {
      key: '_compareComposite',
      value: function _compareComposite(state) {
        var relations = this.getRelation(state);

        if (relations && relations.length > 0) {
          for (var i = 0, l = relations.length; i < l; i++) {
            var _state = relations[i],
                value = this._states[_state]();

            if (value !== this._values[_state]) {
              this._dispatch(_state, this._values[_state], value);
              this._values[_state] = value;
            }
          }
        }
      }
    }, {
      key: '_addRelation',
      value: function _addRelation(state, relations) {
        for (var i = 0, l = relations.length; i < l; i++) {
          var pros = relations[i];
          this._relations[pros].push(state);
        }
      }
    }, {
      key: '_dispatch',
      value: function _dispatch(state, origValue, newValue) {
        if (this._callbacks[state]) {
          this._callbacks[state].fire([newValue, origValue]);
        }
      }
    }, {
      key: 'getRelation',
      value: function getRelation(state) {
        return this._relations[state].length > 0 ? this._relations[state] : null;
      }
    }, {
      key: 'on',
      value: function on(state, callback) {
        if (typeof state === 'function') {
          callback = state;
          state = 'all';
        }

        if (!this._callbacks[state]) {
          this._callbacks[state] = _jquery2.default.Callbacks();
        }
        this._callbacks[state].add(callback);
      }
    }, {
      key: 'off',
      value: function off(state, callback) {
        if (this._callbacks[state]) {
          this._callbacks[state].remove(callback);
        }
      }
    }, {
      key: 'set',
      value: function set(state, value) {
        var isDeep = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

        if (typeof state === 'string' && typeof value !== 'undefined' && typeof this._states[state] !== 'function') {
          this._states[state] = value;
          if (!isDeep) this._compare(state);
        } else if ((typeof state === 'undefined' ? 'undefined' : babelHelpers.typeof(state)) === 'object') {
          for (var _key in state) {
            if (typeof state[_key] !== 'function') {
              this.set(_key, state[_key], true);
            }
          }
          for (var _key2 in state) {
            if (typeof state[_key2] !== 'function') {
              this._compare(_key2);
            }
          }
        }

        return this._states[state];
      }
    }, {
      key: 'get',
      value: function get(state) {
        if (state) {
          return this._values[state];
        } else {
          return this._values;
        }
      }
    }]);
    return _class;
  }();

  exports.default = _class;
});
