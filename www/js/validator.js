$(document).ready(function() {
    $("form.validate").bind("submit", function(ev) {
        var errors = [];
        var form = $(this);
        $(this).find("input").add("textarea").each(function() {
            var val = $(this).val();
            $(this).removeClass("notvalid");

            var control = $(this);
//            if ($(this).hasClass("validate-required") && (val == '' || !val)) {
//                errors.push({
//                    control: $(this),
//                    text: "Необходимо ввести значение" + ($(this).attr('title') ? ' в поле '+$(this).attr('title') : '')
//                });
//            }
//            if ($(this).hasClass("validate-numeric") && (parseInt(val) == 0 || !parseInt(val))) {
//                errors.push({
//                    control: $(this),
//                    text: "Поле" + ($(this).attr('title') ? ' '+$(this).attr('title') : '') + " может принимать только числовое значение"
//                });
//            }
            // advanced
            if (!control.attr("class")) return;
            var cls = control.attr("class").split(" ");
            $.each(cls, function (key, className) {
                if (className.substr(0, 9).toLowerCase() == 'validate-') {
                    var valParams = className.substr(9).split("_");
                    var valType = valParams.splice(0, 1)[0];
                }

                switch (valType) {
                    case "required":
                        if (val == '' || !val) {
                            errors.push({
                                control: control,
                                text: "Необходимо ввести значение" + (control.attr('title') ? ' в поле ' + control.attr('title') : '')
                            });
                        }
                        break;
                    case "numeric":
                        if (parseInt(val) == 0 || !parseInt(val)) {
                            errors.push({
                                control: control,
                                text: "Поле" + (control.attr('title') ? ' ' + control.attr('title') : '') + " может принимать только числовое значение"
                            });
                        }
                        break;
                    case "length":
                        console.log(val.length);
                        if ((valParams[0] && val.length < valParams[0]) || (valParams[1] && val.length > valParams[1])) {
                            errors.push({
                                control: $(this),
                                text: "Символов в поле" +
                                    (control.attr('title') ? ' ' + control.attr('title') : '') +
                                    " должно быть" +
                                    (valParams[0] == valParams[1] ?
                                        " ровно "+valParams[0] :
                                        (
                                            (valParams[0] ? " от " + valParams[0] : "") +
                                            (valParams[1] ? " до " + valParams[1] : "")
                                        )
                                    )
                            });
                        }
                        break;
                }
            });
        });

        if (errors.length > 0) {
            var msg = "";
            $.each(errors, function (key, val) {
                val.control.addClass("notvalid").trigger("focus");
                msg += "<li>" + val.text + "</li>";
            });
            if (msg != "") {
                errorPopup("Некорректно заполнена форма", msg);
            }
            ev.preventDefault();
            return false;
        } else {
//            console.log("all okay");
            return true;
        }

    });
});

function errorPopup(title, text) {
    $(".validate-errors").css("left", ($(document).width() - $(".validate-errors").width()) / 2 );
    $(".base-overlay").show();
    $(".validate-errors .title").html(title);
    $(".validate-errors .error-text").html(text);
    $(".validate-errors").show();
}