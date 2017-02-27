jQuery(document).ready(function($) {

    // Toggle add skill text
    $("#add-skill-btn").click(function() {
        $(this).text(function(i, text) {
            return text === "Hide" ? "Add new" : "Hide";
        })
    });

    $("#sugget_results").fadeOut();
    // Autocomplete
    $("#new_skill").keyup(function() {
        $.post(
            ajaxurl, {
                action: 'search_skill_autocomplete',
                key: $(this).val()
            },
            function(data) {
                $("#sugget_results").fadeIn().html(data);
            }
        );
    });

    $("#sugget_results").on('click', '.skill', function(event) {
        var div = $(this);
        $("#user_skills").append($(this));
        $.post(
            ajaxurl, {
                action: 'add_skill_ajax',
                skill_id: div.attr('data-id')
            },
            function(data) {
                div.append('<i class="fa fa-times" id="' +
                    div.attr('data-id') + '" aria-hidden="true"></i>');
                $("#sugget_results").fadeOut().html('');
            }
        );
    });


    // Remove skill via ajax
    $("#user_skills").on('click', '.skill>i.fa.fa-times', function(event) {
        var _this = $(this);
        $.post(
            ajaxurl, {
                action: 'delete_skill_ajax',
                skill_id: _this.attr('id')
            },
            function(data) {
                if (parseInt(data)) {
                    _this.parent().remove();
                }
            }
        );
        return false;
    });

    // Send data to search freelancers
    $("#search-form").on('click', 'button.btn', function() {
        find_freelancer_by_skill();
    });

    // Submit form to hire freelancer
    $("#search-results").on('click', '.hire-me', function() {
        $(this).siblings('form').submit();
    });


    function find_freelancer_by_skill() {
        var search_results = $("#search-results");
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: { action: 'get_user_by_skill_ajax', skills: $("#search-form input[type='text']").val() },
            beforeSend: function() {
                search_results.fadeIn(2000, function() {
                    search_results.html('<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>');
                });
            },
            success: function(data) {
                search_results.html(data);
            }
        });

    }

    // Review styling
    $("#review_form").on('click', '.fa', function() {
        var index = $(this).index();
        for (var i = 0; i < $("#review_form .fa").length; i++) {

            if (i < index) {
                $("#review_form .fa").eq(i).addClass('rated');
            } else {
                $("#review_form .fa").eq(i).removeClass('rated');
            }
        }
        $('#rate').val(index);
    });

    // $("#review_form .fa").hover(function() {
            //     var index = $(this).index();
            //     for (var i = 0; i < $("#review_form .fa").length; i++) {
            //         if (i < index) {
            //             $("#review_form .fa").eq(i).addClass('rated');
            //         } else {
            //             $("#review_form .fa").eq(i).removeClass('rated');
            //         }
            //     }
            // }, function() {
            //     for (var i = 0; i < $("#review_form .fa").length; i++) {
            //         $("#review_form .fa").eq(i).removeClass('rated');

            //     }
            // });

});
