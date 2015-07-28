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
    $(document).on( "click" , "#calendar td:not(.outOfMonth), table.inner td", function (e) {

        $('#container').css('opacity' , 0.2);

        var url = $(this).attr('data-url');
        url = '/event/new/' + url;

        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $('body').append(data);
            }
        });
    });

    /*
     * -- prevent previous handler to executed on link click
     * 
     */
    $(document).on( "click" , "#calendar td a", function (e) {
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
                $('#container').css('opacity' , 1);
                $.ajax({
                    type: "GET",
                    url: data.referer,
                    cache: true,
                    success: function(data){
                        $("#container").html(data);
                        $.ttm_sizeCalendar();
                    }
                });
            },
            error:function(data) {
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
            }
        }); 
    });

    $(document).on( 'click' , 'a.moreLink', function (e) {

        console.log('clic in show');
        var link = $(this);
        var cell = link.parent();

        cell.css( 'position', 'absolute' );
        cell.css( 'width', cell.parent().width() );
        cell.css( 'background-color', '#060' );
        cell.children('div').css('display' , 'block');
        
        link.removeClass('moreLink');
        link.addClass('closeLink');
        link.html( 'close' );

        console.log('done');
        
    });
    
    $(document).on( 'click' , 'a.closeLink', function (e) {

        console.log('clic in close');
        
        var link = $(this);
        var cell = link.parent();

        var cellHeight = $.ttm_getCellHeight();
        
        // 20 = height of meeting in px
        var maxEvents = parseInt((cellHeight / 20) - 1);

        var eventCount = cell.children('div').length;

        var numItemsToRemove = eventCount - maxEvents + 1;



        if ( numItemsToRemove > 0 ) {

            // hide event which have no place
            cell.find('div:nth-last-child(-n + ' + ( numItemsToRemove + 1 ) + ')').css('display' , 'none');

            var moreLink = $('<a></a>')
                .addClass('moreLink')
                .addClass('align-center')
                .text(numItemsToRemove +  ' more')
            ;



            cell.append(moreLink);
            
            link.remove();
        }

        cell.css( 'position', 'relative' );
        
        console.log('done');
        
    });

});
