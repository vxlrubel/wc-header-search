(function ($) {

    class WCHeaderSearch {
        init() {
            this.createHeaderElement();
        }
        createHeaderElement() {
            alert('done')
        }
    }

    $(document).ready(function () {
        const wchs = new WCHeaderSearch();
        wchs.init();
    });

})(jQuery);