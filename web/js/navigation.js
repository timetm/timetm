$(function() {

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
    $(document).on( 'click' , '#ajaxFrame', function (e) {
        $('#ajaxFrame').remove();
        $('#ttm_calendarContainer').css('opacity' , 1);
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
        var url = $(this).attr('href');
        if (url) {
            $.ajax({
                type: "GET",
                url: url,
                cache: true,
                success: function(data){
                    $("#ttm_calendarContainer").html(data);
                    $.ttm_sizeCalendar();
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

        if ( url.match(/month/) != null ) {
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

});
