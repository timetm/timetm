$(function() {


    if ($(".formError").length > 0) {
        $(".formError").each(function() {
            $(this).parent().css('padding-bottom', '10px').css('background-color', '#900');
        });
    }



    // grey out placeholder in options
    $('select option')
    .filter(function() {
        return !this.value || $.trim(this.value).length === 0;
    })
   .css('color' , '#999');

    // handle paricipant in form
    $(document).on( 'change focusout' , '#timetm_eventbundle_event_contacts', function (e) {

        var selected = $( "#timetm_eventbundle_event_contacts option:selected" );

        if ( selected.val() === '') {
            return;
        }
        console.log(selected.val());

        // get the selected Contact
        var newContact = selected.text();

        // get the content of participants field
        var contacts = $( "#timetm_eventbundle_event_participants").val();

        // if name is already in field do nothing
        var regexp = new RegExp(newContact);
        if ( contacts.match(regexp) ) {
            return;
        }

        if (contacts) {
            contacts += ', ';
        }
        contacts += newContact;
        $( "#timetm_eventbundle_event_participants").val(contacts);
    });


    $("#timetm_eventbundle_event_startdate").datetimepicker({
        format:'d/m/Y H:i',
        step: 15,
        onChangeDateTime:function(dp,$input){
            updateEndDateField(dp,$input);
        }
    });


    $("#timetm_eventbundle_event_enddate").datetimepicker({
        format:'d/m/Y H:i',
        step: 15
    });

});

function toString(param) {

    return param < 10 ? param = '0' + param: param;
}

function updateEndDateField(dp,$input) {

    // get field value
    var dateInput = $input.val();

    // reverse date ( d/m/y -> y/m/d )
    var buffer = dateInput.split(' ');

    var date = buffer[0];
    var time = buffer[1];

    buffer = date.split('/');
    buffer.reverse();
    date = buffer.join('/');

    dateInput = date + ' ' + time;

    // create date object from date input
    var startDate = new Date(dateInput);

    // create new date object
    var dateOutput = new Date();

    // set new date at now +1h
    dateOutput.setTime(startDate.getTime() + (60*60*1000));

    // formatting
    var day = toString(dateOutput.getDate());
    var hours = toString(dateOutput.getHours());
    var mins = toString(dateOutput.getMinutes());

    var month = dateOutput.getMonth();

    month += 1;

    if ( month == 12 ) {
        month = 0;
    }

    month = toString(month);

    dateOutputString = day + '/' + month + '/' + dateOutput.getFullYear() + ' ' + hours + ':' + mins;

    $("#timetm_eventbundle_event_enddate").val(dateOutputString);
}
