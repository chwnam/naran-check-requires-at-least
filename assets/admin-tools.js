jQuery(function ($) {
    var functionInfos = $('.function-info'),
        functionInfoWrap = $('.function-info-wrap'),
        collapsedHint = functionInfoWrap.next();

    $('#collapse-used-functions').on('click', function (e) {
        e.preventDefault();
        collapsedHint.removeClass('hidden');
        functionInfos.slideUp(function () {
            functionInfoWrap.addClass('shrink');
        });
    });

    $('#expand-used-functions').on('click', function (e) {
        e.preventDefault();
        collapsedHint.addClass('hidden');
        functionInfos.slideDown(function () {
            functionInfoWrap.removeClass('shrink');
        });
    });
});