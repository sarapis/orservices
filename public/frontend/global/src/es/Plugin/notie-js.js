// import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'notie';

class Notie extends Plugin {
  getName() {
    return NAME;
  }

  render() {
    this.$el.data('notieApi', this);
  }

  show() {
    let options = this.options;

    if (options.type !== undefined) {
      if (options.type === 'confirm') {
        notie.confirm(options.text, options.yesText, options.noText, () => {
          notie.alert(1, options.yesMsg, 1.5);
        }, () => {
          notie.alert(3, options.noMsg, 1.5);
        });
      }

      if (options.type === 'input') {
        notie.input({
          type: options.inputType,
          placeholder: options.placeholder,
          prefilledValue: options.prefilledvalue
        }, options.text, options.yesText, options.noText, (valueInput) => {
          notie.alert(1, `you entered: ${valueInput}`, 1.5);
        }, (valueInput) => {
          notie.alert(3, `You cancelled with this value: ${valueInput}`, 2);
        });
      }

      if (options.type === 'success') {
        notie.alert(1, options.text, options.delay);
      }

      if (options.type === 'warning') {
        notie.alert(2, options.text, options.delay);
      }

      if (options.type === 'error') {
        notie.alert(3, options.text, options.delay);
      }

      if (options.type === 'info') {
        notie.alert(4, options.text, options.delay);
      }

      if (options.animationdelay !== undefined && typeof options.animationdelay === 'number') {
        notie.setOptions({
          animationDelay: options.animationDelay
        });
      }

      if (options.bgclickdismiss !== undefined && typeof options.bgclickdismiss === 'boolean') {
        notie.setOptions({
          backgroundClickDismiss: options.bgclickdismiss
        });
      }

    }
  }

  static api() {
    return 'click|show';
  }
}

Plugin.register(NAME, Notie);

export default Notie;
