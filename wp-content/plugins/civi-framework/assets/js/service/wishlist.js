var WISHLIST = WISHLIST || {};
(function ($) {
	"use strict";

	WISHLIST = {
		init: function () {
			var wishlist_save = civi_template_vars.wishlist_save,
				wishlist_saved = civi_template_vars.wishlist_saved,
				ajax_url = civi_template_vars.ajax_url;

			$("body").on("click", ".civi-service-wishlist", function (e) {
				e.preventDefault();
				if (!$(this).hasClass("on-handle")) {
					var $this = $(this).addClass("on-handle"),
						service_id = $this.attr("data-service-id"),
						save = "";

					$.ajax({
						type: "post",
						url: ajax_url,
						dataType: "json",
						data: {
							action: "civi_service_wishlist",
							service_id: service_id,
						},
						beforeSend: function () {
							$this
								.find(".icon-heart")
								.html('<span class="civi-dual-ring"></span>');
						},
						success: function (data) {
							if (data.added) {
								save = wishlist_saved;
								$this.removeClass("removed").addClass("added");
								$this
									.parents(".civi-service-item")
									.removeClass("removed-wishlist");
							} else {
								save = wishlist_save;
								$this.removeClass("added").addClass("removed");
								$this.parents(".civi-service-item").addClass("removed-wishlist");
							}

							$this.children("i").removeClass("fa-spinner fa-spin");
							if (typeof data.added == "undefined") {
								console.log("login?");
							}
							$this.removeClass("on-handle");
							$this.html(
								'<div class="icon-heart"><i class="fas fa-heart"></i></div>'
							);
						},
						error: function (xhr) {
							var err = eval("(" + xhr.responseText + ")");
							console.log(err.Message);
							$this.children("i").removeClass("fa-spinner fa-spin");
							$this.removeClass("on-handle");
						},
					});
				}
			});
		},
	};
	$(document).ready(function () {
		WISHLIST.init();
	});
})(jQuery);
