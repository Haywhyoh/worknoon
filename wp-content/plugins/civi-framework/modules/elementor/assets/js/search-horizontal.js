(function ($) {
    "use strict";

    var HorizontalSearchHandler = function ($scope, $) {
        var search_form = $scope.find('.civi-search-horizontal');
        var filter_search = search_form.find('#search-horizontal_filter_search');
        var available = filter_search.data("key");

        filter_search.autocomplete({
            source: available,
            minLength: 0,
            autoFocus: true,
            focus: true,
        }).focus(function() {
            $(this).data("uiAutocomplete").search($(this).val());
        });

        search_form.find(".civi-clear-top-filter").on("click", function () {
            filter_search.val("");
            search_form.find(".civi-select2").val("");
			search_form.find(".civi-select2").select2("destroy");
			var list_select = search_form.find(".civi-select2");
			list_select.each( function() {
				var option = $( this ).find( 'option' );
				if (theme_vars.enable_search_box_dropdown == 1) {
					if( option.length > theme_vars.limit_search_box ){
						$( this ).select2();
					} else {
						$( this ).select2({
							minimumResultsForSearch: -1,
						});
					}
				} else {
					$( this ).select2({
						minimumResultsForSearch: -1,
					});
				}
			});
			$( '.select2.select2-container' ).on( 'click', function() {
				var options = $(this).prev().find( 'option' );
				options.each( function() {
					var option_val = $( this ).val();
					var level = $( this ).attr('data-level');
					$( '.select2-results li[id$="' + option_val + '"]' ).attr( 'data-level', level );
				});
			});
			$( '.civi-form-location .icon-arrow i' ).on( 'click', function() {
				var options = $(this).closest('.civi-form-location').find( 'select.civi-select2 option' );
				options.each( function() {
					var option_val = $( this ).val();
					var level = $( this ).attr('data-level');
					$( '.select2-results li[id$="' + option_val + '"]' ).attr( 'data-level', level );
				});
			});
        });
    };

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/civi-search-horizontal.default",
            HorizontalSearchHandler
        );
    });
})(jQuery);
