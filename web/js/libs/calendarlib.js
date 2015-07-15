(function($) {

    // test function
    $.ttm_test = function() {
        console.log('calendarlib.js loaded');
    }


    /*
     * -- calculate calendar cell height
     * 
     */
    $.ttm_getCellHeight = function() {

    var cal = '#calendar',
    rows = cal + ' tr';

    // get container height
    var displayHeight = $('#content').height();

    // get number of rows
    var rowCount = $(rows).length;

        // for weeks divide by 7days
        if ( $(cal).attr('data-week') != undefined ) {
            rowCount = ($(rows).length / 7);
        }

        // calculate cell height ( we remove rowCount = 1px border )
        return (displayHeight - rowCount )  / rowCount;
    }


    /*
     * -- set calendar cell height
     * 
     */
    $.ttm_setCellHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            var cellHeight = $.ttm_getCellHeight();            
        }

        // set cell heigth
        $('#calendar td').css( 'height' , cellHeight );
    }
    
    
    /*
     * -- set event cell height
     * 
     */
    $.ttm_setEventHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            var cellHeight = $.ttm_getCellHeight();            
        }

        var cellList = $('#calendar .event');

        $(cellList).each(function() {

            var scale = $(this).attr('data-duration');

            var startmins = $(this).attr('data-startmins') / 0.6;

            var top = startmins * cellHeight / 100;

            var evenHeight = ( cellHeight ) * scale;

            $(this).css( 'height' , evenHeight );
            $(this).css( 'top' , top );
        });
    }


    $.ttm_handleMonthEvents = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            var cellHeight = $.ttm_getCellHeight();            
        }

        // 20 = height of meeting in px
        var maxEvents = parseInt((cellHeight / 20) - 1);
        
        var cellList = $('#calendar .monthEventWrapper');

        $(cellList).each(function() {

            // undo previous cell hiding
            $(this).find('div').css('display' , 'block');

            // get number of event
            var eventList = $(this).children('div');
            var eventCount = eventList.length;

            if ( eventCount > maxEvents - 1) {
                // hide event which have no place
                $(this).find('div:nth-last-child(-n + ' + ( eventCount - maxEvents) + ')').css('display' , 'none');
            }
        });
    }

    /*
     * -- set event cell height
     * 
     */
    $.ttm_sizeCalendar = function() {

        // get cell height
        var cellHeight = $.ttm_getCellHeight();

        $.ttm_setCellHeight(cellHeight);
        if (document.querySelector('.event') !== null) {
            $.ttm_setEventHeight(cellHeight);
        }
        else if (document.querySelector('.monthEventWrapper') !== null) {
            $.ttm_handleMonthEvents(cellHeight);
        }
    }

}(jQuery));
