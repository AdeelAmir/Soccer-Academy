$(document).ready(function () {
    'use strict';
    let Element = $(".repeater-default");
    let Element2 = $(".repeater-custom-show-hide");

    if(Element.length > 0){
        Element.repeater();
    }

    if(Element2.length > 0){
        Element2.repeater({
            show: function () {
                $(this).slideDown();
                NumberQuizQuestions();
                $(".timepicker").timepicker();
                if ($("#AddUserPage").length) {
                    NumberDocumentNumbers();
                }
                if ($(".hide-data-repeater-btn").length > 0) {
                    $(".hide-data-repeater-btn").attr('disabled', false);
                }
            },
            hide: function (remove) {
                $(this).slideUp(remove, function () {
                    $(this).remove();
                    NumberQuizQuestions();
                    if ($("#AddUserPage").length) {
                        NumberDocumentNumbers();
                    }
                });
            }
        });
    }
});

function NumberQuizQuestions() {
    let QuestionNumber = 0;
    const elements = document.querySelectorAll('.add_quiz_question_label');
    Array.from(elements).forEach((element, index) => {
        // conditional logic here.. access element
        QuestionNumber++;
        element.innerHTML = "Question " + QuestionNumber;
    });
}
