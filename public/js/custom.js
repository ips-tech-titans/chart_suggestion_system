$(document).ready(function () {
    $(".js-select2").select2({
        minimumResultsForSearch: Infinity
    });
    $(".js-select2-multi").select2({
        maximumSelectionLength: 3
    });

    $(".large").select2({
        dropdownCssClass: "big-drop",
    });

});


//   CSV File Upload

