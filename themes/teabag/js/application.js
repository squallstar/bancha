var myApp = (function ($) {
    // Thank you @dwightjack for the bindhooks
    // All here is PRIVATE
    
    var bindHooks = function (hooks, context) {
			var c = context || this,
				h;
			if (!hooks) {
				return;
			}
			
			for (h in hooks) {
				var $els = $(h) || [];
				if ($els.length > 0) {
					hooks[h].call(c, h, $els);
				}
			}
		},
		config = {
			// Your configuration
			language: 'en',
			defaults: {
				// Your default values
			}
		},
		helper = {
			// Your helpers
		},
		handler = {
			// Your handlers
		};
    return {
        // All here is PUBLIC
        init: function () {
            var hooks = {
                // Usage Sample
				//'#wrapper' : function (sl, $el) {
				//	$el.hide().fadeIn();
				//}
			 
			};
			bindHooks(hooks);
			return this;
        }
    };
})(jQuery);
$(document).ready(myApp.init);