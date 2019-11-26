// import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'loadingButton';

class LoadingButton extends Plugin {
  getName() {
    return NAME;
  }

  render() {
    this.text = this.$el.text();
    this.$el.data('loadingButtonApi', this);
  }

  loading() {
    let $el = this.$el,
      i = this.options.time,
      loadingText = this.options.loadingText,
      opacity = this.options.opacity;

    $el.text(`${loadingText}(${i})`).css('opacity', opacity);

    let timeout = setInterval(() => {
      $el.text(`${loadingText}(${--i})`);
      if (i === 0) {
        clearInterval(timeout);
        $el.text(text).css('opacity', '1');
      }
    }, 1000);
  }

  static api() {
    return 'click|loading';
  }

  static getDefaults() {
    return {
      loadingText: 'Loading',
      time: 20,
      opacity: '0.6'
    };
  }
}

Plugin.register(NAME, LoadingButton);

export default LoadingButton;
