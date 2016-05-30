/**
 *   function init all listeners for generate batch box
 */

function loadListeners() {
    tags = [];
    reitchanged = false;
    $('#add-tags').on('click',
        function () {
            var tagElement = $('#tag');
            if(!validateTag(tagElement)) return;
            var id = tags.length;
            tags[id] = tagElement[0].value;
            $('#list-group').append('<div class="clearfix" id="tag-line-' + id + '"><a class="tag-list tag-item">' + tags[id]+ '</a>' +
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

    $('#budget').on('input',
        function () {
            var tagElement = $('#budget');
            if(!validateBudget(tagElement)) return;
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
            if( budget !== undefined && budget!== "") url += "budget="+budget+"&";
            var reit = $('#reit-value').text();
            if(reit !== undefined && reit !== "" && reitchanged ) url += "reit="+reit+"&";
            var deadline = $('#deadline')[0].value;
            if(deadline !== undefined && deadline !== "") url += "deadline="+deadline+"&";
            if(tags.length > 0) url += "tags="+tags.join(',');
            if(url !== "")
            window.location.href = "filter?"+url.replace(/&$/,'');

        }
    );

    $('#make-request').on('click',
        function () {
            $('#bid-request-form').removeClass('no-show');
            validationInit();
            return false;
        }
    );

    $('#cancel').on('click',
        function () {
            $('#bid-request-form').addClass('no-show');
            return false;
        }
    );
    //--------------------------------------------------------------------



    $('#delete').on('click',
        function () {
            var ids_for_deleting = [];
            $("input:checkbox:checked").each(function () {
                ids_for_deleting.push($(this).val());
            });
            if (ids_for_deleting.length == 0) {
                return false;
            }
            if (!confirm("Are you sure you want to delete these items?")) {
                return false;
            }
            var url = location.pathname[location.pathname.length - 1] == '/' ? location.pathname.substring(0, location.pathname.length - 1) : location.pathname;
            $.ajax({
                type: 'POST',
                url: url + '/delete',
                data: {
                    '_token': $('#token')[0].value,
                    'ids[]': ids_for_deleting
                },
                cache: false,
                success: function () {
                    location.reload();
                }
            });
            return false;
        }
    );



    $('#generate').on('click',
        function () {
            validationDuration();
            validationDate();
            if ($('.invalid').length > 0 || participants_list.length == 0) {
                return false;
            }
            var index = $('#certificate-style')[0].selectedIndex;
            var style = $('#certificate-style')[0][index].value;
            $('#bid-request-form').addClass('no-show');
            var url = location.pathname[location.pathname.length - 1] == '/' ? location.pathname.substring(0, location.pathname.length - 1) : location.pathname;
            $.ajax({
                type: 'POST',
                url: url + '/generate',
                data: {
                    '_token': $('#token')[0].value,
                    'participiant[]': participants_list,
                    'date': $('#date')[0].value,
                    'duration': $('#duration')[0].value,
                    'theme': style
                },
                cache: false,
                success: function (html) {
                    $('#list-group').empty();
                    participants_list = null;
                    participants_list = [];
                    location.reload();
                }
            });
            return false;
        }
    );


    $('#duration').on('blur', function (e) {
        var reg = /^[0-9]+$/
        if (!e.target.value.match(reg)) {
            $(e.target).addClass('invalid');
        } else {
            $(e.target).removeClass('invalid');
        }
    });

    $('#date').on('blur', function () {
        var date = $('#date');
        if (date[0].value == '') {
            date.addClass('invalid');
        } else {
            date.removeClass('invalid');
        }
    });

    $("#date").datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: validationDate
    });

    $('.edit-button').on('click',
        function () {
            var url = location.pathname[location.pathname.length - 1] == '/' ? location.pathname.substring(0, location.pathname.length - 1) : location.pathname;
            $.ajax({
                type: 'get',
                url: url + '/edit/' + this.id,
                cache: false,
                success: function (html) {
                    $('body').prepend(html.body);
                    validationInit();
                    $('#save-edit').on('click',
                        submitEdit
                    );
                    $('#cancel-edit').on('click',
                        function () {
                            $('#certificate-edit-form').remove();
                            return false;
                        }
                    );
                }
            });
            return false;
        }
    );
    var clipboard = new Clipboard('.copy-to-clipboard-button');
}
/**
 *  function submit edit certificate form
 * @returns {boolean}
 */
function submitEdit() {
    if ($('.name-text').hasClass("invalid")
        || $('#edit-first-name')[0].value == '' || $('#edit-last-name')[0].value == '') {
        return false;
    }
    var url = location.pathname[location.pathname.length - 1] == '/' ? location.pathname.substring(0, location.pathname.length - 1) : location.pathname;
    $.ajax({
        type: 'POST',
        url: url + '/edit',
        data: {
            '_token': $('#token')[0].value,
            'id': document.forms['edit-form'].id.value,
            'first_name': document.forms['edit-form']['edit-first-name'].value,
            'last_name': document.forms['edit-form']['edit-last-name'].value,
            'status': document.forms['edit-form']['edit-status'].value
        },
        cache: false,
        success: function () {
            location.reload();
        }
    });
}

/**
 * function add event for validate text fields first and last name
 */
function validationInit() {
    $('.name-text').on('blur', function (e) {
        if (e.target.value != '') {
            var reg = /^[a-zA-Zа-яіїєґА-ЯІЇЄҐ]+$/;
            if (!e.target.value.match(reg)) {
                $(e.target).addClass('invalid');
            } else {
                $(e.target).removeClass('invalid');
            }
        }
    });
}



/**
 * function validate duration
 */
function validationDuration() {
    var reg = /^[0-9]+$/;
    var duration = $('#duration');
    validate(duration, reg);
}
/**
 * function validate date
 */
function validationDate() {
    var date = $('#date');
    if (date[0].value == '') {
        date.addClass('invalid');
    } else {
        date.removeClass('invalid');
    }
}


