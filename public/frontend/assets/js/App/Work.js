(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Work', ['exports', 'BaseApp'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('BaseApp'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.BaseApp);
    global.AppWork = mod.exports;
  }
})(this, function (exports, _BaseApp2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppWork = undefined;

  var _BaseApp3 = babelHelpers.interopRequireDefault(_BaseApp2);

  var AppWork = function (_BaseApp) {
    babelHelpers.inherits(AppWork, _BaseApp);

    function AppWork() {
      babelHelpers.classCallCheck(this, AppWork);
      return babelHelpers.possibleConstructorReturn(this, (AppWork.__proto__ || Object.getPrototypeOf(AppWork)).apply(this, arguments));
    }

    babelHelpers.createClass(AppWork, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppWork.prototype.__proto__ || Object.getPrototypeOf(AppWork.prototype), 'processed', this).call(this);

        this.items = [];

        this.handleChart();
        this.handleSelective();
      }
    }, {
      key: 'handleChart',
      value: function handleChart() {
        /* create line chart */
        var scoreChart = function scoreChart(data) {

          var scoreChart = new Chartist.Line(data, {
            labels: ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'],
            series: [{
              name: 'series-1',
              data: [0.8, 1.5, 0.8, 2.7, 2.4, 3.9, 1.1]
            }, {
              name: 'series-2',
              data: [2.2, 3, 2.7, 3.6, 1.5, 1, 2.9]
            }]
          }, {
            lineSmooth: Chartist.Interpolation.simple({
              divisor: 100
            }),
            fullWidth: true,
            chartPadding: {
              right: 25
            },
            series: {
              'series-1': {
                showArea: false
              },
              'series-2': {
                showArea: false
              }
            },
            axisX: {
              showGrid: false
            },
            axisY: {
              scaleMinSpace: 40
            },
            plugins: [Chartist.plugins.tooltip()],
            low: 0,
            height: 250
          });

          scoreChart.on('draw', function (data) {
            if (data.type === 'point') {
              var parent = new Chartist.Svg(data.element._node.parentNode);
              parent.elem('line', {
                x1: data.x,
                y1: data.y,
                x2: data.x + 0.01,
                y2: data.y,
                class: 'ct-point-content'
              });
            }
          });
        };

        // let WeekLabelList = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        // let WeekSeries1List = {
        //   name: 'series-1',
        //   data: [0.8, 1.5, 0.8, 2.7, 2.4, 3.9, 1.1]
        // };
        // let WeekSeries2List = {
        //   name: 'series-2',
        //   data: [2.2, 3, 2.7, 3.6, 1.5, 1, 2.9]
        // };

        /* create bar chart */
        var barChart = function barChart(data) {
          var barChart = new Chartist.Bar(data, {
            labels: ['Damon', 'Jimmy', 'Jhon', 'Alex', 'Lucy', 'Peter', 'Chris'],
            series: [[3.3, 3.5, 2.5, 2, 3.7, 2.7, 1.9], [2, 4, 3.5, 2.7, 3.3, 3.5, 2.5]]
          }, {
            axisX: {
              showGrid: false
            },
            axisY: {
              showGrid: false,
              scaleMinSpace: 30
            },
            height: 210,
            seriesBarDistance: 24
          });

          barChart.on('draw', function (data) {
            if (data.type === 'bar') {
              var parent = new Chartist.Svg(data.element._node.parentNode);
              parent.elem('line', {
                x1: data.x1,
                x2: data.x2,
                y1: data.y2,
                y2: 0,
                class: 'ct-bar-fill'
              });

              data.element.attr({
                style: 'stroke-width: 20px'
              });
            }
          });
        };

        /* run chart */
        $(document).on('slidePanel::afterLoad', function () {
          scoreChart('.trends-chart');
          barChart('.member-chart');
        });
      }
    }, {
      key: 'handleSelective',
      value: function handleSelective() {
        var _this2 = this;

        var self = this;
        var member = [{
          id: 'uid_1',
          name: 'Herman Beck',
          avatar: '../../../../global/portraits/1.jpg'
        }, {
          id: 'uid_2',
          name: 'Mary Adams',
          avatar: '../../../../global/portraits/2.jpg'
        }, {
          id: 'uid_3',
          name: 'Caleb Richards',
          avatar: '../../../../global/portraits/3.jpg'
        }, {
          id: 'uid_4',
          name: 'June Lane',
          avatar: '../../../../global/portraits/4.jpg'
        }, {
          id: 'uid_5',
          name: 'June Lane',
          avatar: '../../../../global/portraits/5.jpg'
        }, {
          id: 'uid_6',
          name: 'June Lane',
          avatar: '../../../../global/portraits/6.jpg'
        }, {
          id: 'uid_7',
          name: 'June Lane',
          avatar: '../../../../global/portraits/7.jpg'
        }];

        var getNum = function getNum(num) {
          return Math.ceil(Math.random() * (num + 1));
        };

        var getMember = function getMember() {
          return member[getNum(member.length - 1) - 1];
        };

        var isSame = function isSame(items) {
          var _items = items;
          var _member = getMember();

          if (_items.indexOf(_member) === -1) {
            return _member;
          }
          return isSame(_items);
        };

        var pushMember = function pushMember(num) {
          var items = [];
          for (var i = 0; i < num; i++) {
            items.push(isSame(items));
          }
          _this2.items = items;
        };

        var setItems = function setItems(membersNum) {
          var num = getNum(membersNum - 1);
          pushMember(num);
        };

        $('.plugin-selective').each(function () {

          setItems(member.length);

          var items = self.items;

          $(this).selective({
            namespace: 'addMember',
            local: member,
            selected: items,
            buildFromHtml: false,
            tpl: {
              optionValue: function optionValue(data) {
                return data.id;
              },
              frame: function frame() {
                return '<div class="' + this.namespace + '">\n                ' + this.options.tpl.items.call(this) + '\n                <div class="' + this.namespace + '-trigger">\n                ' + this.options.tpl.triggerButton.call(this) + '\n                <div class="' + this.namespace + '-trigger-dropdown">\n                ' + this.options.tpl.list.call(this) + '\n                </div>\n                </div>\n                </div>';

                // i++;
              },
              triggerButton: function triggerButton() {
                return '<div class="' + this.namespace + '-trigger-button"><i class="md-plus"></i></div>';
              },
              listItem: function listItem(data) {
                return '<li class="' + this.namespace + '-list-item"><img class="avatar" src="' + data.avatar + '">' + data.name + '</li>';
              },
              item: function item(data) {
                return '<li class="' + this.namespace + '-item"><img class="avatar" src="' + data.avatar + '" title="' + data.name + '">' + this.options.tpl.itemRemove.call(this) + '</li>';
              },
              itemRemove: function itemRemove() {
                return '<span class="' + this.namespace + '-remove"><i class="md-minus-circle"></i></span>';
              },
              option: function option(data) {
                return '<option value="' + this.options.tpl.optionValue.call(this, data) + '">' + data.name + '</option>';
              }
            }
          });
        });
      }
    }]);
    return AppWork;
  }(_BaseApp3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppWork();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppWork;
  exports.AppWork = AppWork;
  exports.run = run;
  exports.getInstance = getInstance;
});
