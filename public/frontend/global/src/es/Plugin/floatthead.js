import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'floatThead';

class FloatThead extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {
      position: 'auto',
      top: function() {
        let offset = $('.page').offset();

        return offset.top;
      },
      responsiveContainer: function($table) {
        return $table.closest('.table-responsive');
      }
    };
  }
}

Plugin.register(NAME, FloatThead);

export default FloatThead;
