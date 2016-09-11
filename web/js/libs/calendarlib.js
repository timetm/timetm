(function($) {

    // test function
    $.ttm_test = function() {
        console.log('calendarlib.js loaded');
    };

    /*
     * -- calculate calendar cell width
     *
     */
    $.ttm_setCellWidth = function() {

         var cellWidth =
            Math.floor(
                parseInt(
                    $("#ttm_calendar").width()) / parseInt($("#ttm_calendar").find("tr:first td").length
                )
            ) - 1; // border

         $(".monthEvent").css('width', cellWidth);
         $(".singleEvent").css('width', cellWidth);
     };

    /*
     * -- calculate calendar cell height
     *
     */
    $.ttm_getCellHeight = function() {

        var cal = '#ttm_calendar',
        rows = cal + ' tr';

        // get container height
        var displayHeight = $('#ttm_contentWithPanel').height();

        // get number of rows
        var rowCount = $(rows).length;


        if ( $(cal).attr('data-month') !== undefined ) {
            rowCount = Math.ceil(rowCount / 16);
        }


        // for weeks divide by 7days
        if ( $(cal).attr('data-week') !== undefined ) {
            rowCount = ($(rows).length / 7);
        }

        if ( $(cal).attr('data-month') !== undefined ) {
            return ((displayHeight -  rowCount)   / rowCount -1 ) ;
        }

        // calculate cell height ( we remove rowCount = 1px border )
        return (displayHeight - rowCount)  / rowCount;
    };


    /*
     * -- set calendar cell height
     *
     */
    $.ttm_setCellHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            cellHeight = $.ttm_getCellHeight();
        }

        // set cell heigth
        $('#ttm_calendar td').css( 'height' , cellHeight );

        if (document.querySelector('.singleEvent') !== null) {
            $('#ttm_calendar .singleEvent a.event').css( 'line-height' , cellHeight + 'px' );
        }
    };


    /*
     * -- set event cell height
     *
     */
    $.ttm_setEventHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            cellHeight = $.ttm_getCellHeight();
        }

        var cellList = $('#ttm_calendar .singleEvent');

        $(cellList).each(function() {

            var scale = $(this).attr('data-duration');

            var startmins = $(this).attr('data-startmins') / 0.6;

            var top = startmins * cellHeight / 100;

            var evenHeight = ( cellHeight ) * scale;

            $(this).css( 'height' , evenHeight );
            $(this).css( 'top' , top );
        });
    };


    /*
     * -- handle number of events to display based on screen size
     *
     */
    $.ttm_handleMonthEvents = function(cellHeight) {

        // undo previous cell hiding
        $('#ttm_calendar .monthEventWrapper div').css('display' , 'block');

        // remove more link
        $('.moreLink').remove();

        // get cell height
        if (!cellHeight) {
            cellHeight = $.ttm_getCellHeight();
        }

        // 20 = height of meeting in px
        var maxEvents = parseInt((cellHeight / 20) - 1);

        var cellList = $('#ttm_calendar .monthEventWrapper');

        $(cellList).each(function() {

            $.ttm_hideMonthEvents($(this), maxEvents);
        });
    };



    /*
     * -- hide events from one cell
     *
     */
    $.ttm_hideMonthEvents = function(cell, maxEvents) {

        var numItems = cell.find('div').length;

        // if we have only one item do nothing
        if (numItems === 1) {
            return;
        }

        // get number of event
        var eventCount = cell.children('div').length;

        var numItemsToRemove = eventCount - maxEvents + 1;

        if ( numItemsToRemove > 1 ) {

            // hide event which have no place
            cell.find('div:nth-last-child(-n + ' + parseInt(numItemsToRemove - 1) + ')').css('display' , 'none');

            var moreLink = $('<a></a>')
                .addClass('moreLink')
                .addClass('align-center')
                .text(parseInt(numItemsToRemove - 1) +  ' more')
            ;

            cell.append(moreLink);
        }
    };


    /*
     * -- set event cell height
     *
     */
    $.ttm_sizeCalendar = function() {

        // console.log('run');

        // get cell height
        var cellHeight = $.ttm_getCellHeight();

        // remove "view all events" close links
        $('.closeLink').remove();

        // reset event width
        $('.monthEventWrapper').css('position' ,  'relative');
        $('.monthEventWrapper').css('width' ,  'auto');

        $.ttm_setCellHeight(cellHeight);
            $.ttm_setEventHeight(cellHeight);
            $.ttm_handleMonthEvents(cellHeight);

        $.ttm_setCellWidth();


    };

}(jQuery));
