$(function() {

    // grey out placeholder in options
    // $('select option').filter(function() {
    //     return !this.value || $.trim(this.value).length === 0;
    // })
    // .css('color' , '#f00');

    // handle paricipant in form
    $(document).on( 'change focusout' , '#timetm_eventbundle_event_contacts', function (e) {

        $.ttm_updateParticipantsField();
    });

});
