// import $ from 'jquery';
import Plugin from 'Plugin';
import * as Config from 'Config';

const NAME = 'donut';

class DonutPlugin extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {
      lines: 12,
      angle: 0.3,
      lineWidth: 0.08,
      pointer: {
        length: 0.9,
        strokeWidth: 0.035,
        color: Config.colors('grey', 400)
      },
      limitMax: false, // If true, the pointer will not go past the end of the gauge
      colorStart: Config.colors('grey', 200),
      colorStop: Config.colors('grey', 200),
      strokeColor: Config.colors('primary', 500),
      generateGradient: true
    };
  }

  render() {
    if (!Gauge) {
      return;
    }

    let $el = this.$el;
    let $canvas = $el.find('canvas'),
      $text = $el.find('.donut-label');

    if ($canvas.length === 0) {
      return;
    }

    let donut = new Donut($canvas[0]).setOptions(this.options);

    $el.data('donut', donut);

    donut.animationSpeed = 50;
    donut.maxValue = $el.data('max-value');

    donut.set($el.data('value'));

    if ($text.length > 0) {
      donut.setTextField($text[0]);
    }
  }
}

Plugin.register(NAME, DonutPlugin);

export default DonutPlugin;
