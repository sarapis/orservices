import $ from 'jquery';

export default class {
  constructor(states) {
    this._states = Object.assign({}, states);
    this._values = {};
    this._relations = {};
    this._callbacks = {};
    this._define();
  }

  _define() {
    let self = this,
      keys = Object.keys(this._states),
      obj = {},
      tmpRelations = [],
      composites = [];

    for (let i = 0, l = keys.length; i < l; i++) {
      let key = keys[i],
        value = this._states[key];
      if (typeof value !== 'function') {
        Object.defineProperty(obj, key, {
          set() {
            return false;
          },
          get() {
            tmpRelations.push(key);
            return self._states[key];
          },
          enumerable: true,
          configurable: true
        });
        this._values[key] = this._states[key];
        this._relations[key] = [];
      } else {
        composites.push(key);
      }
    }

    for (let i = 0, l = composites.length; i < l; i++) {
      let key = composites[i];
      Object.defineProperty(obj, key, {
        set() {
          return false;
        },
        get() {
          let value = self._states[key].call(obj);
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
    }
  }

  _compare(state) {
    if (this._states[state] !== this._values[state]) {
      let value = this._values[state];
      this._values[state] = this._states[state];
      this._dispatch(state, value, this._states[state]);
      this._compareComposite(state);
    }
  }

  _compareComposite(state) {
    let relations = this.getRelation(state);

    if (relations && relations.length > 0) {
      for (let i = 0, l = relations.length; i < l; i++) {
        let state = relations[i],
          value = this._states[state]();

        if (value !== this._values[state]) {
          this._dispatch(state, this._values[state], value);
          this._values[state] = value;
        }
      }
    }
  }

  _addRelation(state, relations) {
    for (let i = 0, l = relations.length; i < l; i++) {
      let pros = relations[i];
      this._relations[pros].push(state);
    }
  }

  _dispatch(state, origValue, newValue) {
    if (this._callbacks[state]) {
      this._callbacks[state].fire([newValue, origValue]);
    }
  }

  getRelation(state) {
    return this._relations[state].length > 0 ? this._relations[state] : null;
  }

  on(state, callback) {
    if (typeof state === 'function') {
      callback = state;
      state = 'all';
    }

    if (!this._callbacks[state]) {
      this._callbacks[state] = $.Callbacks();
    }
    this._callbacks[state].add(callback);
  }

  off(state, callback) {
    if (this._callbacks[state]) {
      this._callbacks[state].remove(callback);
    }
  }

  set(state, value, isDeep = false) {
    if (typeof state === 'string' && typeof value !== 'undefined' && typeof this._states[state] !== 'function') {
      this._states[state] = value;
      if (!isDeep) this._compare(state);
    } else if (typeof state === 'object') {
      for (let key in state) {
        if (typeof state[key] !== 'function') {
          this.set(key, state[key], true);
        }
      }
      for (let key in state) {
        if (typeof state[key] !== 'function') {
          this._compare(key);
        }
      }
    }

    return this._states[state];
  }

  get(state) {
    if (state) {
      return this._values[state];
    } else {
      return this._values;
    }
  }
}
