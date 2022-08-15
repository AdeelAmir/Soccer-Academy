/**
 * Theme: Crovex - Responsive Bootstrap 4 Admin Dashboard
 * Author: Mannatthemes
 * Form Repeater
 */

$(document).ready(function () {
    'use strict';

    $('.repeater-default').repeater();

    $('.repeater-custom-show-hide').repeater({
        show: function () {
            $(this).slideDown();
            NumberQuizQuestions();
            NumberDocumentNumbers();
            AddSaleFormFields();
        },
        hide: function (remove) {
            $(this).slideUp(remove, function () {
                $(this).remove();
                NumberQuizQuestions();
                NumberDocumentNumbers();
            });
            // if (confirm('Are you sure you want to remove this item?')) {
            //     $(this).slideUp(remove);
            // }
        }
    });
});