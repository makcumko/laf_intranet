$().ready(function() {
    $(document).bind("click", function(evt) {
        if (($(evt.target).parents(".popup").size() == 0 && !$(evt.target).hasClass("popup")) || $(evt.target).hasClass("popup-close")) {
            $(".popup").hide();
        }
        return true;
    });

});