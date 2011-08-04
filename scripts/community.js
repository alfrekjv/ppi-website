/*$("#twitter").click(
    function () {
        $(".source-github").fadeOut();
        return false;
    }
);

$("#github").click(
    function () {
        $(".source-twitter").fadeOut();
        return false;
    }
);*/

$("#twitter").live("click", function( ) {
    $(".source-github").fadeOut();
    $(".source-twitter").fadeIn();

    return false;
});

$("#github").live("click", function( ) {
    $(".source-twitter").fadeOut();
    $(".source-github").fadeIn();

    return false;
});

$("#all").live("click", function( ) {
    $(".source-twitter").fadeIn();
    $(".source-github").fadeIn();

    return false;
});