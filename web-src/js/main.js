/**
 * Created by vitaliy on 3/12/16.
 */


$(function () {

    var deputats = [];

    $.each( $('li[data-href]'), function(index,el){

        $(el).click(function (e) {
            var player = $('#player');
            console.info(e);
            player.prop('src', $(el).attr('data-href'));
        });

    });



    var substringMatcher = function (strs) {

        return function findMatches(q, cb) {
            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            console.log(q);

            substrRegex = new RegExp(q, 'i');

            $.each(strs, function (i, str) {
                if (substrRegex.test(str.full)) {
                    matches.push(str.shortname);
                }
            });

            cb(matches);
        };
    };


    $('.typeahead').typeahead({
            minLength: 3,
            highlight: true
        },
        {
            name: 'my-dataset',
            source: substringMatcher(deputats)
        }).bind('typeahead:select', function (ev, suggestion) {

        window.location = '/search/'+suggestion;
    });

    $.ajax({
        dataType: "json",
        url: 'http://cors.io/?u=http://www.chesno.org/persons/json/deputies/8/?format=json',
        success: function (data) {

            $.each(data, function (i, deputat) {

                var d = {
                    "shortname": deputat.last_name + ' ' + deputat.first_name[0] + '.' + deputat.second_name[0] + '.',
                    "full": deputat.last_name + ' ' + deputat.first_name + ' ' + deputat.second_name + ' '
                };
                deputats.push(d);

            });

            console.log(deputats);
        }
    });


    $(document).ready(function () {
        $('.down-btn').click(function () {
            //$('.down-btn').hide();
            $('html, body').animate({
                scrollTop: $(".player").offset().top
            }, 1000);
        });
    });
});
