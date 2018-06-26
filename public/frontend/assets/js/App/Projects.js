(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Projects', ['exports', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Site);
    global.AppProjects = mod.exports;
  }
})(this, function (exports, _Site2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppProjects = undefined;

  var _Site3 = babelHelpers.interopRequireDefault(_Site2);

  var AppProjects = function (_Site) {
    babelHelpers.inherits(AppProjects, _Site);

    function AppProjects() {
      babelHelpers.classCallCheck(this, AppProjects);
      return babelHelpers.possibleConstructorReturn(this, (AppProjects.__proto__ || Object.getPrototypeOf(AppProjects)).apply(this, arguments));
    }

    babelHelpers.createClass(AppProjects, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppProjects.prototype.__proto__ || Object.getPrototypeOf(AppProjects.prototype), 'processed', this).call(this);

        this.handleSelective();
        this.handleProject();
      }
    }, {
      key: 'handleSelective',
      value: function handleSelective() {
        var members = [{
          id: 'uid_1',
          name: 'Herman Beck',
          img: '../../../../global/portraits/1.jpg'
        }, {
          id: 'uid_2',
          name: 'Mary Adams',
          img: '../../../../global/portraits/2.jpg'
        }, {
          id: 'uid_3',
          name: 'Caleb Richards',
          img: '../../../../global/portraits/3.jpg'
        }, {
          id: 'uid_4',
          name: 'June Lane',
          img: '../../../../global/portraits/4.jpg'
        }],
            selected = [{
          id: 'uid_1',
          name: 'Herman Beck',
          img: '../../../../global/portraits/1.jpg'
        }, {
          id: 'uid_2',
          name: 'Caleb Richards',
          img: '../../../../global/portraits/2.jpg'
        }];

        $('.plugin-selective').selective({
          namespace: 'addMember',
          local: members,
          selected: selected,
          buildFromHtml: false,
          tpl: {
            optionValue: function optionValue(data) {
              return data.id;
            },
            frame: function frame() {
              return '<div class="' + this.namespace + '">\n            ' + this.options.tpl.items.call(this) + '\n          <div class="' + this.namespace + '-trigger">\n            ' + this.options.tpl.triggerButton.call(this) + '\n          <div class="' + this.namespace + '-trigger-dropdown">\n            ' + this.options.tpl.list.call(this) + '\n          </div>\n          </div>\n          </div>';
            },
            triggerButton: function triggerButton() {
              return '<div class="' + this.namespace + '-trigger-button"><i class="md-plus"></i></div>';
            },
            listItem: function listItem(data) {
              return '<li class="' + this.namespace + '-list-item"><img class="avatar" src="' + data.img + '">' + data.name + '</li>';
            },
            item: function item(data) {
              return '<li class="' + this.namespace + '-item"><img class="avatar" src="' + data.img + '">' + this.options.tpl.itemRemove.call(this) + '</li>';
            },
            itemRemove: function itemRemove() {
              return '<span class="' + this.namespace + '-remove"><i class="md-minus-circle"></i></span>';
            },
            option: function option(data) {
              return '<option value="' + this.options.tpl.optionValue.call(this, data) + '">' + data.name + '</option>';
            }
          }
        });
      }
    }, {
      key: 'handleProject',
      value: function handleProject() {
        $(document).on('click', '[data-tag=project-delete]', function (e) {
          bootbox.dialog({
            message: 'Do you want to delete the project?',
            buttons: {
              success: {
                label: 'Delete',
                className: 'btn-danger',
                callback: function callback() {
                  // $(e.target).closest('.list-group-item').remove();
                }
              }
            }
          });
        });
      }
    }]);
    return AppProjects;
  }(_Site3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppProjects();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppProjects;
  exports.AppProjects = AppProjects;
  exports.run = run;
  exports.getInstance = getInstance;
});
