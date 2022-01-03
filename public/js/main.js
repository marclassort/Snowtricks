/* Show or remove tricks */

$(function () {
    $("div.trick").slice(0, 3).show();
    $("#loadMoreTrick").on('click', function (e) {
        e.preventDefault();
        $("div.trick:hidden").slice(0, 3).slideDown();
        if ($("div.trick:hidden").length == 0) {
            $("#loadMoreTrick").hide('slow');
            $("#loadLessTrick").show('slow');
        }
    });
    $("#loadLessTrick").on('click', function (e) {
        e.preventDefault();
        $("div.trick").slice(3, $("div.trick").length).hide();
        $("#loadLessTrick").hide('slow');
        $("#loadMoreTrick").show('slow');

    });
});
