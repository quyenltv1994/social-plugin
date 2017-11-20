jQuery(function($) {
    jQuery(document).ready(function () {
        if($(".asocials").length > 0) {
            var $grid = $('.asocials__wrapper--container').isotope({
                itemSelector: '.sw__item'
            });
            jQuery(".asocials__wrapper-icons li a").click(function (e) {
                e.preventDefault();
                var filter = $(this).data('filter');
                $grid.isotope({filter: filter});
            });
            var initShow = 3; //number of items loaded on init & onclick load more button
            var counter = initShow; //counter for load more button
            var iso = $grid.data('isotope'); // get Isotope instance

            loadMore(initShow); //execute function onload

            function loadMore(toShow) {
                $grid.find(".hidden").removeClass("hidden");

                var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function (item) {
                    return item.element;
                });
                $(hiddenElems).addClass('hidden');
                $grid.isotope('layout');

                //when no more to load, hide show more button
                if (hiddenElems.length == 0) {
                    jQuery("#load-more").hide();
                } else {
                    jQuery("#load-more").show();
                }
                ;

            }

            //append load more button
            $grid.after('<button id="load-more"> Load More</button>');

            //when load more button clicked
            $("#load-more").click(function () {
                if ($('#filters').data('clicked')) {
                    //when filter button clicked, set initial value for counter
                    counter = initShow;
                    $('#filters').data('clicked', false);
                } else {
                    counter = counter;
                }
                ;

                counter = counter + initShow;

                loadMore(counter);
            });

            //when filter button clicked
            $("#filters").click(function () {
                $(this).data('clicked', true);

                loadMore(initShow);
            });
        }
    });
});