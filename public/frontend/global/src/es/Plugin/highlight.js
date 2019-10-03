import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'highlight';

class Highlight extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {};
  }

  render() {
    if (typeof $.fn.hightlight === 'undefined') {
      return;
    }

    // hljs.configure({useBR: true});
    hljs.highlightBlock(block);
  }
}

Plugin.register(NAME, Highlight);

export default Highlight;
