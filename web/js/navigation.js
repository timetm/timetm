$(function() {

    /*
     * -- show calendar quick nav
     *
     */
    $(document).on( 'click' , '#mobilePanel', function (e) {
        e.preventDefault();

        // hide mobile menu
        if ($(".showForMediumInlineBlock").hasClass('showMenu')) {
            showMobileMenu();
        }

        $("#PanelMonthCal td").toggleClass("sizeMobileNavCalendar");

        // show panel
        $("#ttm_panel").toggleClass("showPanel");
    });

    /*
     * -- show mobile menu
     *
     */
    $(document).on( 'click' , '#mobileMenu, #closeMenu a', function (e) {

        e.preventDefault();

        if ($("#ttm_panel").hasClass("showPanel")) {
            $("#ttm_panel").toggleClass("showPanel");
        }

        showMobileMenu();
    });



    /*
     * -- clickable tr
     *
     */
    $('tr[data-href]').on("click", function() {
        document.location = $(this).data('href');
    });


   /*
     * -- close ajax frame
     *
     */
    $(document).on( 'click' , '#ajaxFrame, #ajaxFrame #eventBackButton', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $('#ajaxFrame').remove();
        console.log('clicked on close ajax frame');
    });

    /*
     * -- prevent closing ajax frame when clicking on ajaxContent
     *
     */
    $(document).on( 'click' , '#ajaxContent', function (e) {
        return false;
    });


    /*
     * -- handle main calendar prev/next links
     *
     */
    $(document).on( "click" , "#panelCalendarNav td a, #panelCalendarMode td a", function (e) {
        e.preventDefault();

        var hasPanel = false;

        if ($("#ttm_panel").hasClass("showPanel")) {
            console.log("has panel");
            hasPanel = true;
        }

        var url = $(this).attr('href');
        if (url) {
            $.ajax({
                type: "GET",
                url: url,
                cache: true,
                success: function(data){
                    $("#ttm_calendarContainer").html(data);
                    $.ttm_sizeCalendar();
                    $("#ttm_panel").toggleClass("showPanel");
                }
            });
        }
        console.log( 'clicked in navigation : ' + url);
    });


    /*
     * -- handle "quick navigation" prev/next links
     *
     */
    $(document).on( "click" , "#panelCalendarQuickNav td a", function (e) {
        e.preventDefault();
        var url = $(this).attr('href') + '/panel';
        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $("#panelCalendar").html(data);
            }
        });
        console.log( 'clicked in quick nav prev/next : ' + url);
    });


    /*
     * -- handle "quick navigation" day links
     *
     */
    $(document).on( "click" , "#PanelMonthCal td a", function (e) {

        e.preventDefault();
        var url = $(this).attr('href');

        if ( url.match(/month/) !== null ) {
            var day = $(this).val();
            url += '/content';
            console.log( 'matched ' + url);
        }

        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $("#ttm_calendarContainer").html(data);
                $.ttm_sizeCalendar();
            }
        });
        console.log( 'clicked in quick nav day : ' + url);
    });

    /*
     * -- handle click on event
     *
     */
    $(document).on( "click" , "#ttm_calendar td a.event", function (e) {

        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $('body').append(data);
            }
        });
        console.log( 'clicked in calendar event : ' + url);
    });

    /*
     * -- click on event edit in ajaxframe
     *
     */
    $(document).on( "click" , "#ajaxFrame .button", function (e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data) {
                $('#ajaxFrame').remove();
                $('body').append(data);
            }
        });

        console.log( 'clicked on button on ajaxframe : ' + url);
    });


});

function showMobileMenu() {

    $(".showForMediumInlineBlock").toggleClass('showMenu');
    $(".showForMediumInlineBlock li").toggleClass('showMenuItem');
    $("#logo").toggleClass('hide');
    $("#closeMenu").toggleClass('show');
}
