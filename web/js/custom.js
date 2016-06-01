/**
 *   function init all listeners for generate batch box
 */

function loadListeners() {
    tags = [];
    reitchanged = false;
    $('#add-tags').on('click',
        function () {
            var tagElement = $('#tag');
            if (!validateTag(tagElement)) return;
            var id = tags.length;
            tags[id] = tagElement[0].value;
            $('#list-group').append('<div class="clearfix" id="tag-line-' + id + '"><a class="tag-list tag-item">' + tags[id] + '</a>' +
                '<a href="#" class="tag-list-drop" name="' + id + '">&#9003;</a></div>');
            tagElement[0].value = '';
            $('.tag-list-drop').on('click', function (e) {
                var id = e.target.name;
                $('#tag-line-' + id).remove();
                delete tags[id];
                return false;
            });
            return false;
        }
    );

    $('#reit').on('input',
        function (event) {
            reitchanged = true;
            $('#reit-value').text(event.target.value);
            return true;
        }
    );

    $('#expected-reit').on('input',
        function (event) {
            reitchanged = true;
            $('#expected-reit-value').text(event.target.value);
            return true;
        }
    );

    $('#budget').on('input',
        function () {
            var tagElement = $('#budget');
            if (!validateBudget(tagElement)) return;
            return true;
        }
    );

    /**
     * function validate text fields first and last name
     */
    function validateBudget(object) {
        var reg = /^-?\d*\.?\d*$/;
        return validate(object, reg);
    }

    /**
     * function validate text fields first and last name
     */
    function validateTag(object) {
        var reg = /^[a-zA-Zа-яіїєґА-ЯІЇЄҐ#\+ 0-9]+$/;
        return validate(object, reg);
    }

    /**
     * function validate jquery input object accordingly to regexp
     *
     * @param object jquery object for input[type="text"]
     *
     * @param regexp regexp object for validation criteria
     */
    function validate(object, regexp) {
        var result = true;
        if (object.hasClass("invalid") || object[0].value == '') {
            result = false;
        }
        if (!object[0].value.match(regexp)) {
            object.addClass('invalid');
            result = false;
        } else {
            object.removeClass('invalid');
        }
        return result;
    }

    $("#deadline").datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: validationDate
    });
    /**
     * function validate date
     */
    function validationDate() {
        var date = $('#deadline');
        if (new Date(date[0].value) < Date.now()) {
            date.addClass('invalid');
        } else {
            date.removeClass('invalid');
        }
    }

    $('#filter').on('click',
        function () {
            var url = "";
            var budget = $('#budget')[0].value;
            if (budget !== undefined && budget !== "") url += "budget=" + budget + "&";
            var reit = $('#reit-value').text();
            if (reit !== undefined && reit !== "" && reitchanged) url += "reit=" + reit + "&";
            var deadline = $('#deadline')[0].value;
            if (deadline !== undefined && deadline !== "") url += "deadline=" + deadline + "&";
            if (tags.length > 0) url += "tags=" + tags.join(',');
            if (url !== "")
                window.location.href = "filter?" + url.replace(/&$/, '');

        }
    );

    $('#make-request').on('click',
        function () {
            if(!$('#logined').length) {
                window.location.href = "https://accounts.google.com/o/oauth2/auth?redirect_uri=http://lance.local.com/auth&response_type=code&client_id=663315718006-67cmagfdoitr0i3ra6c2bsj4vllbno8k.apps.googleusercontent.com&scope=https://www.googleapis.com/auth/userinfo.email%20https://www.googleapis.com/auth/userinfo.profile";
            } else {
                $('#bid-request-form').removeClass('no-show');
            }
            return false;
        }
    );

    $('#cancel').on('click',
        function () {
            $('#bid-request-form').addClass('no-show');
            return false;
        }
    );

    $('#generate').on('click',
        function () {
            var tagElement = $('#comment').val();
            if(tagElement.length < 50) {
                $('#comment').addClass('invalid');
                return false;
            } else {
                $('#comment').removeClass('invalid');
            }
            $.ajax({
                type: 'POST',
                url: window.location.href + '/bid',
                data: {
                    'comment': tagElement
                },
                cache: false,
                success: function () {
                    $('#bid-request-form').addClass('no-show');
                }
            });
        }
    );

    $('.finish').on('click',
        function (event) {
            $(this).addClass('deleted');
            var id = event.target.id;
            $('#project-id').val(event.target.id);
            $('#project-estimate-form').removeClass('no-show');
            return false;
        }
    );
    $('#estimate').on('click',
        function () {
            $.ajax({
                type: 'PUT',
                url: window.location.href + '/' + $('#project-id').val() + '/reit/' + $('#reit').val(),
                cache: false,
                success: function () {
                    $('#project-estimate-form').addClass('no-show');
                    $('#reit-' + $('#project-id').val()).html($('#reit').val());
                    $('#status-' + $('#project-id').val()).html('Finished');
                    $('.deleted').remove();
                }
            });
            return false;
        }
    );
    $('#add').on('click',
        function () {
            $('#project-add-form').removeClass('no-show');
            return false;
        }
    );
    $('#create').on('click',
        function () {
            var name = $('#name')[0].value;
            var description = $('#description').val();
            var budget = $('#budget')[0].value;
            var reit = $('#reit-value').text();
            var deadline = $('#deadline')[0].value;
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: {
                    'name': name,
                    'description': description,
                    'budget': budget,
                    'reit': reit,
                    'deadline': deadline,
                    'tags': tags.join(',')
                },
                cache: false,
                success: function () {
                    $('#project-add-form').addClass('no-show');
                    window.location.reload();
                }
            });
            return false;
        }
    );
}
