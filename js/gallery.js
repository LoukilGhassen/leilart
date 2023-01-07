var start = 0 ;
var limit = 10 ;
var reachedMax = false ;
var searching = false;
$(document).ready(function () {
    getData()
});
$(window).scroll(function () {

    if ($(window).scrollTop() + 200 > $(document).height() - $(window).height() && !searching )
    {
        getData();
    }
});
function getData() {
    if (reachedMax)
        return;
    if (!searching) {
        searching = true
        $.ajax({
            url: 'data.php',
            method: 'POST',
            data: {
                getData: 1,
                start: start,
                limit: limit
            },
            success: function (response) {
                searching = false;
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