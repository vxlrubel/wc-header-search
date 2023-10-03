(function ($) {

    class WCHeaderSearch {
        init() {
            this.createHeaderElement();
        }
        createHeaderElement() {
            var headerElement = `
            <div class="wch-header">
                <div class="container">
                    <div class="contact-info">
                        <span>01714240934</span>
                    </div>
                    <div class="search-parent">
                        <div class="logo">
                            <a href="#"><img src="${wch.logo_url}" alt="logo image"></a>
                        </div>
                        <form action="#" class="wch-search-form">
                            <input type="text" id="wch-search-field">
                        </form>
                        <ul class="search-result">
                            <li><a href="#">Search Result</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            `;
            $('body').prepend(headerElement);
        }
    }

    $(document).ready(function () {
        const wchs = new WCHeaderSearch();
        wchs.init();
    });

})(jQuery);