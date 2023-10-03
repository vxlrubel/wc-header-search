(function ($) {

    class WCHeaderSearch {
        init() {
            this.createHeaderElement();
            this.getSearchResult();
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
                        <ul class="search-result"></ul>
                    </div>
                </div>
            </div>
            `;
            $('body').prepend(headerElement);
        }

        getSearchResult() {
            $('#wch-search-field').on('keyup', function (e) {
                e.preventDefault();
                // $(this)
                //     .parent('.wch-search-form')
                //     .siblings('.search-result')
                //     .append('hello world');
                $.ajax({
                    type: 'GET',
                    url: wch.ajaxurl,
                    data: {
                        action: 'wch_get_products',
                        terms: $(this).val().trim().toLowerCase()
                    },
                    success: function (response) {
                        console.log(response.data);
                    },
                    beforeSend: function () {
                        $(this)
                            .parent('.wch-search-form')
                            .siblings('.search-result')
                            .append('loading');
                    }
                });
            });
        }
    }

    $(document).ready(function () {
        const wchs = new WCHeaderSearch();
        wchs.init();
    });

})(jQuery);