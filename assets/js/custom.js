(function ($) {

    class WCHeaderSearch {
        init() {
            this.createHeaderElement();
            this.getSearchResult();
        }
        createHeaderElement() {
            var headerElement = `
            <div class="wch-header">
                <div class="contact-info" style="background-color: ${wch.whatsapp_bg}; color: ${wch.whatsapp_color}">
                    <span>${wch.whatsapp}</span>
                </div>
                <div class="container">
                    <div class="search-parent">
                        <div class="logo">
                            <a href="${wch.site_url}"><img src="${wch.logo_url}" alt="logo image"></a>
                        </div>
                        <form action="#" class="wch-search-form">
                            <input type="text" id="wch-search-field">
                            <button class="wch-search-icon"><i class="fas fa-search"></i></button>
                        </form>
                        <ul class="search-result" id="show-search-result"></ul>
                    </div>
                </div>
            </div>
            `;
            $('body').prepend(headerElement);
        }

        getSearchResult() {
            $('#wch-search-field').on('keyup', this.sendAjaxRequest);
            $('#wch-search-field').on('change', this.sendAjaxRequest);
            $('.wch-search-icon').on('click', this.sendAjaxRequest);
            $('.wch-search-form').on('submit', this.sendAjaxRequest);
        }
        sendAjaxRequest() {
            $.ajax({
                type: 'GET',
                url: wch.ajaxurl,
                data: {
                    action: 'wch_get_products',
                    terms: $('#wch-search-field').val().trim().toLowerCase()
                },
                success: function (response) {
                    var data = response.data;
                    if (data && data.length) {

                        // clear the search result
                        $('#show-search-result').empty();

                        // count the total array of element
                        let count = data.length;

                        for (let i = 0; i < count; i++) {

                            let element = `
                            <li class="wc-product-list">
                                <div class="thumb">
                                    <img src="${data[i].thumb}">
                                </div>
                                <div class="desc">
                                    <h2><a href="${data[i].links}">${data[i].title}</a></h2>
                                    <p>${data[i].content}</p>
                                </div>
                            </li>
                            
                            `;

                            // append the element of the search result
                            if (data[i].not_found) {
                                $('#show-search-result').append('No Product Found');
                            } else {
                                $('#show-search-result').append(element);
                            }

                        }
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        const wchs = new WCHeaderSearch();
        wchs.init();
    });

})(jQuery);