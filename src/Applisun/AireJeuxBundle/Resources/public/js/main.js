var score = {
    
    formId: "",
    selectedInputId: "",
    
    initialize: function(formId, selectedInputId) {
        this.formId= formId;
        this.selectedInputId= selectedInputId;
        this.bindEvents();
    },
    
    bindEvents: function() {
        var scoreItem = $("span[data-selector='.item_score']");
        scoreItem.on({
            click: score.onClickEvent,
            mouseenter: score.onMouseEnterEvent,
            mouseleave: score.onMouseLeaveEvent
          });
    },
    
    onClickEvent: function() {
        $('#'+score.selectedInputId).val($(this).attr('data-score'));   
        $('#'+score.formId).submit();
    },
    
    onMouseEnterEvent: function(){
        $(this).nextAll(".casquette").removeClass('full').addClass('empty');
        $(this).prevAll(".casquette").removeClass('empty').addClass('full');
        $(this).removeClass('empty').addClass('full');
    },
    onMouseLeaveEvent: function() {        
        $(this).addClass('empty').removeClass('full');
    }
};

score.initialize('form_score', 'vote_aire_score');

