$(function() {

    // onload
//    $.ttm_test();
    $.ttm_sizeCalendar();

    // on resize
    var timer;

    $(window).resize(function() {

        if(timer) {
            window.clearTimeout(timer);
        }

        timer = window.setTimeout(function() {
            $.ttm_sizeCalendar();
        }, 30);
    });


    /*
     * -- handle new event from calendar - show create form
     *
     */
    $(document).on( "click" , "#ttm_calendar td:not(.outOfMonth), table.inner td", function (e) {

        var url = $(this).attr('data-url');
        url = '/event/new/' + url;

        History.pushState({urlPath: url}, null, url);
    });

    /*
     * -- prevent previous handler to executed on link click
     *
     */
    $(document).on( "click" , "#ttm_calendar td a", function (e) {
        e.stopPropagation();
    });


    /*
     * -- handle create event from calendar - send create form
     *
     */
    $(document).on( 'click' , '#ajaxFrame #timetm_eventbundle_event_save', function (e) {

        var form = $('#event_save');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data){
                $('#ajaxFrame').remove();
                History.pushState({urlPath: data.referer}, null, data.referer);
            },
            error:function(data) {
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
                $.ttm_highlightFormErrors();
                $.ttm_initEventDatetimepicker();
            }
        });
    });



    $(document).on( 'click' , 'a.moreLink', function (e) {

        // get link and cell
        var link = $(this);
        var cell = link.parent();

        // set cell css
        cell.css( 'position', 'absolute' );
        cell.css( 'z-index', 5 );
        cell.css( 'width', cell.parent().width() );
        cell.addClass('expanded');
        // cell.css( 'background-color', '#060' );

        // show all events
        cell.children('div').css('display' , 'block');

        // change link class and text
        link.removeClass('moreLink');
        link.addClass('closeLink');
        link.html( 'close' );
    });



    $(document).on( 'click' , 'a.closeLink', function (e) {

        // get cell
        var link = $(this);
        var cell = link.parent();
        link.remove();

        // get cell height
        var cellHeight = $.ttm_getCellHeight();

        // get max evenrts ( 20 = height of meeting in px )
        var maxEvents = parseInt((cellHeight / 20) - 1);

        // hide events
        $.ttm_hideMonthEvents(cell, maxEvents);

        // set cell css
        cell.css( 'position', 'relative' );
        cell.css( 'z-index', 0 );
        cell.css( 'width', 'auto' );
        cell.css( 'background-color', 'transparent' );
        cell.removeClass('expanded');

    });

});
