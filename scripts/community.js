$(".topcontent .filter-twitter").click(function() {
    $(".source-github").fadeOut();
    $(".source-twitter").fadeIn();
    return false;
});


$(".topcontent .filter-github").click(function() {
    $(".source-github").fadeIn();
    $(".source-twitter").fadeOut();
    return false;
});

$(".topcontent .filter-all").click(function() {
    $(".source-github").fadeIn();
    $(".source-twitter").fadeIn();
    return false;
});