var searchingCategory = false;
const queryString = window.location.search;
const parameters = new URLSearchParams(queryString);
const categor = parameters.get('categor');
var categoryName = "Categorie introuvable"
var searchingCategoryImages = false;
var start = 0;
var limit = 10;
var reachedMax = false;

$(document).ready(function () {
    getCategory()
    getCategoryImages()
});
$(window).scroll(function () {
    if ($(window).scrollTop() + 200 > $(document).height() - $(window).height() && !searchingCategoryImages )
    {
        getCategoryImages()
    }
});


function getCategory() {
    if (!searchingCategory) {

        searchingCategory = true
        $.ajax({
            url: 'categoryData.php',
            method: 'POST',
            data: {
                categor: categor,
            },
            success: function (response) {
                categoryName = response
                searchingCategory = false;
                $(".categorie").append(categoryName);

            }
        });
    }
}

function getCategoryImages() {
    if (reachedMax)
        return;
    if (!searchingCategoryImages) {
        searchingCategoryImages = true
        $.ajax({
            url: 'categoryData.php',
            method: 'POST',
            data: {
                categorImages: categor,
                start: start,
                limit: limit
            },
            success: function (response) {
                searchingCategoryImages = false;
                if (response === "reachedMax") {
                    reachedMax = true;
                } else {
                    $(".imgcontain").append(response);
                    start = start + limit;
                    if ($(window).width() > 700) {
                        $('.image')
                            .parent()
                            .zoom();
                    }
                }
            }
        });
    }
}