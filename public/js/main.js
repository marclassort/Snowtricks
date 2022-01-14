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

/* Add another collection widget */
jQuery(document).ready(function () {

jQuery('.add-another-collection-widget').click(function (e) {

var list = jQuery(jQuery(this).attr('data-list-selector'));
var counter = list.data('widget-counter') || list.children().length;

var newWidget = list.attr('data-prototype');
newWidget = newWidget.replace(/__name__/g, counter);
counter++;
list.data('widget-counter', counter);

var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
newElem.appendTo(list);
});
});

const addFormToCollection = (e) => {
const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

const item = document.createElement('li');

item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

collectionHolder.appendChild(item);

collectionHolder.dataset.index ++;
};

document.querySelectorAll('.add_item_link').forEach(btn => btn.addEventListener("click", addFormToCollection));

