(function ($) {
    'use strict';

    /*when the DOM is ready*/
    $(function () {

        var cookieNames = document.cookie.split(/=[^;]*(?:;\s*|$)/);
// Remove any that match the pattern
        for (var i = 0; i < cookieNames.length; i++) {
            if (/^segment_/.test(cookieNames[i])) {
                var currentCookie = cookieNames[i];
                var currentValue = Cookies.get(currentCookie);
                Cookies.set(currentCookie, '', {path: '/'});
                Cookies.remove(currentCookie, '', {path: '/'});
            }
        }

        var cookieNames = document.cookie.split(/=[^;]*(?:;\s*|$)/);
        for (var i = 0; i < cookieNames.length; i++) {
            if (/^segment_/.test(cookieNames[i])) {
                var currentCookie = cookieNames[i];
                var currentValue = Cookies.get(currentCookie);
                var x = 0;
                if (currentValue != null && x < 500) {
                    Cookies.set(currentCookie, '', {path: '/'});
                    x++;
                    Cookies.remove(currentCookie, '', {path: '/'});
                }
            }
        }

    });

})(jQuery);