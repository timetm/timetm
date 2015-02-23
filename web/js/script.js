$(function() {
  
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
        }
      });
    }
    console.log( 'clicked in navigation : ' + url);
  });
  
});