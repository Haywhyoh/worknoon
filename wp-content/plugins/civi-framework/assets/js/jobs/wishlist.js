var WISHLIST = WISHLIST || {};
(function ($) {
	"use strict";

	WISHLIST = {
		init: function () {
			var wishlist_save = civi_template_vars.wishlist_save,
				wishlist_saved = civi_template_vars.wishlist_saved,
                package_expires = civi_template_vars.package_expires,
				ajax_url = civi_template_vars.ajax_url;

			$("body").on("click", ".civi-add-to-wishlist", function (e) {
				e.preventDefault();
				if (!$(this).hasClass("on-handle")) {
					var $this = $(this).addClass("on-handle"),
						jobs_inner = $this
							.closest(".jobs-inner")
							.addClass("jobs-active-hover"),
						jobs_id = $this.attr("data-jobs-id"),
						save = "";

					$.ajax({
						type: "post",
						url: ajax_url,
						dataType: "json",
						data: {
							action: "civi_add_to_wishlist",
							jobs_id: jobs_id,
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
									.parents(".civi-jobs-item")
									.removeClass("removed-wishlist");
							} else {
								save = wishlist_save;
								$this.removeClass("added").addClass("removed");
								$this.parents(".civi-jobs-item").addClass("removed-wishlist");
							}
							if(data.package_expires){
                                $this.removeClass("added").removeClass("removed");
                                alert(package_expires);
                                window.location.reload();
							}

							$this.children("i").removeClass("fa-spinner fa-spin");
							if (typeof data.added == "undefined") {
								console.log("login?");
							}
							$this.removeClass("on-handle");
							jobs_inner.removeClass("jobs-active-hover");
							$this.html(
								'<div class="icon-heart"><i class="fas fa-heart"></i></div>'
							);
						},
						error: function (xhr) {
							var err = eval("(" + xhr.responseText + ")");
							console.log(err.Message);
							$this.children("i").removeClass("fa-spinner fa-spin");
							$this.removeClass("on-handle");
							jobs_inner.removeClass("jobs-active-hover");
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
