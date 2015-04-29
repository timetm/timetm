$(function() {
  
  // TODO move
  
  setCellHeight();
  
  
  /*
   * handle new event from calendar - show create form
   */
  $(document).on( "click" , "#MonthCal td", function (e) {
      
      $('#container').css('opacity' , 0.2);
      
      var url = $(this).attr('data-url');
      
      console.log();
      
      url = url.replace(/-/g, '/');
      
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
   * prevent previous handler to executed on link click
   */
  $(document).on( "click" , "#MonthCal td a", function (e) {
      e.prevenPropagation();
  });
  
  
  
  /*
   * handle create event from calendar - send create form
   */
  var clic = 0;
  $(document).on( 'click' , '#timetm_eventbundle_event_save', function (e) {

      console.log('DEBUG start :');
//      console.log($('timetm_eventbundle_event_title').val());
      if ( $('timetm_eventbundle_event_title').val()  != "") {
          console.log('DEBUG : title has a val');
          $('#titleerror').remove();
      }
      
      var form = $('#event_save');
      /*
       * Throw the form values to the server!
       */
      console.log('sending ajax.');
      $.ajax({
          type: form.attr('method'),
          url: form.attr('action'),
          data: form.serialize(),
          dataType: 'json',
      })
      .done(function (data) {
          console.log('receiving response.');
          console.log();
          
          if(data['success']===true) {
              $('#ajaxFrame').remove();
              $('#container').css('opacity' , 1);
              $.ajax({
                  type: "GET",
                  url: '/month',
                  cache: true,
                  success: function(data){
                    $("#container").html(data);
                    setCellHeight();
                  }
                });
          }
          else {
              if ( ! $('timetm_eventbundle_event_title').val() ) {
                  $("label[for='timetm_eventbundle_event_title']").after('<div id="titleerror">' + data.errorReport.title[0] + '</div>');
              }
              else {
                  $('#titleerror').remove();
              }
              return;
          }
          

      })

  });


  /*
   * close ajax frame 
   */
  $(document).on( 'click' , '#ajaxFrame', function (e) {
      
      $('#ajaxFrame').remove();
      $('#container').css('opacity' , 1);
  });
  
  $(document).on( 'click' , '#ajaxContent', function (e) {
      return false;
  });
  
  /*
   * -- AJAX call for panel "quick navigation" day links
   */
  $(document).on( "click" , "#PanelMonthCal td a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    console.log( 'clicked 1 ' + url);
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
         $("#container").html(data);
      }
    });
    console.log( 'clicked 2 ' + url);
  });
  
  /*
   * -- AJAX call for panel "quick navigation" prev/next links
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
    console.log( 'clicked in quick nav : ' + url);
  });
  
  /*
   * -- AJAX call for calendar prev/next links
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
          $("#container").html(data);
          setCellHeight();
        }
      });
    }
    console.log( 'clicked in navigation : ' + url);
  });
  
});

function setCellHeight() {

    var displayHeight = $('#MonthCal').height();
    var rowCount = $('#MonthCal tr').length;
    var cellHeight = (displayHeight - (rowCount * 10))  / rowCount;
    
    $('#MonthCal td').css( 'height' , cellHeight );
}