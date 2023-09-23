var SERVICE = SERVICE || {};
(function ($) {
    "use strict";

    SERVICE = {
        init: function () {
            this.total_price();
            this.submit_addons();
        },

        total_price: function () {
            var packageAddons = $('.package-addons'),
                packageTotal = $('.package-total').find('.number'),
                startPrice = $('.package-total').data('start-price');

            packageAddons.find('input[type="checkbox"]').click(function(){
                var priceTotal = startPrice;
                packageAddons.find('input:checkbox:checked').each(function(){
                    priceTotal += isNaN(parseInt($(this).val())) ? 0 : parseInt($(this).val());
                });
                packageTotal.text(priceTotal);

                var priceAddons = 0;
                packageAddons.find('input:checkbox:checked').each(function(){
                    priceAddons += isNaN(parseInt($(this).val())) ? 0 : parseInt($(this).val());
                });
                $('.service-package-sidebar').find('input[name="price_addons"]').val(priceAddons);
            });
        },

        submit_addons: function () {
            var ajax_url = civi_template_vars.ajax_url,
                payment_url = civi_addons_vars.payment_url;

            $("body").on("click", "#btn-submit-addons", function (e) {
                var packageWarrper = $('.service-package-sidebar'),
                    service_id = packageWarrper.find('input[name="service_id').val(),
                    price_total = packageWarrper.find('.package-total .number').text(),
                    price_addons = packageWarrper.find('input[name="price_addons').val();

                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: ajax_url,
                    dataType: "json",
                    data: {
                        action: "civi_service_addons",
                        service_id: service_id,
                        price_total: price_total,
                        price_addons: price_addons,
                    },
                    beforeSend: function () {
                        packageWarrper.find(".btn-loading").fadeIn();
                    },
                    success: function (data) {
                        if (data.success == true) {
                            window.location.href = payment_url;
                        }
                        packageWarrper.find(".btn-loading").fadeOut();
                    },
                });
            });
        },
    };
    $(document).ready(function () {
        SERVICE.init();
    });
})(jQuery);
