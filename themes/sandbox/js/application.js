var myApp = myApp || {};

myApp = (function () {
    // All here is PRIVATE
	var bindHooks = function (hooks, context) {
		var c = context || this,
			h;
		if (!hooks) {
			return;
		}
		
		for (h in hooks) {
			var els = $(h) || [];
			if (els.length > 0) {
				hooks[h].call(c, h);
			}
		}
	};
    return {
        // All here is PUBLIC
        init: function () {
            var hooks = {
            	// Usage Sample
				//'#wrapper' : function () {
				//	$('#wrapper').hide().fadeIn();
				//}
			 
			};
			bindHooks(hooks);
			return this;
        }
    };
})();

myApp.config = {
	// Your configuration
};

myApp.helper = {
	// Your helpers
};

myApp.handler = {
	// Your handlers
};


(function(){ 
	myApp.init(); 
})(); 
