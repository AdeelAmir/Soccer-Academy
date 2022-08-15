function SetStepActive(e, Call = '') {
    let StepCount = $(e).attr('id').split('_')[1];
    const elements = document.querySelectorAll('.StepProgress-item');
    let Steps = elements.length;
    Array.from(elements).forEach((element, index) => {
        // conditional logic here.. access element
        $(element).removeClass('complete');
    });
    if(Call === ''){
        $(e).addClass('complete');
    }

    /*Setting Respective Card Show*/
    for (let k = 1; k <= Steps; k++){
        $("#barStepContent_" + k).hide();
    }
    $("#barStepContent_" + StepCount).show();
}