const $ = require('jquery');

require('bootstrap');

$(document).ready(function () {
    $("#appbundle_contact_town").keyup(function () {
        let input = $(this).val();
        if (input.length >= 2) {
            fetch('/town/list/' + input, {
                method: "POST"
            }).then(function (response) {
                return response.json();
            }).then(function (json) {
                let towns = json.map((town) => {
                    let highlightedTown = town.town.replace(input.toUpperCase(), `<span>${input.toUpperCase()}</span>`);
                    return `<li>${highlightedTown}</li>`;
                });

                $('#autocomplete').html(towns.join(''));
                $('#autocomplete li').on('click', function () {
                    $('#appbundle_contact_town').val($(this).text());
                    $('#autocomplete').html('');
                });
            }).catch(function () {
                $('#autocomplete').text('Ajax call error');
            });
        } else {
            $('#autocomplete').html('');
        }
    });
});