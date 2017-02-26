jQuery(document).ready(function($) {
    $("#add-skill-btn").click(function() {
        $(this).text(function(i, text) {
            return text === "Hide" ? "Add new" : "Hide";
        })
    });

    $("#add-new-skill").on('click', function() {
        var _this = $(this);
        $.post(
            ajaxurl, {
                action: 'add_skill_ajax',
                new_skill: _this.parent().find('input[name="new_skill"]').val()
            },
            function(data) {
                $("#user_skills").append('<span class="skill">' +
                    _this.parent().find('input[name="new_skill"]').val() +
                    '  <i class="fa fa-times" id="' + data + '" aria-hidden="true"></i></span>');
                _this.parent().find('input[name="new_skill"]').val('').focus();
            }
        );
        return false;
    });

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


    $("#search-form").on('click', 'button.btn', function() {
        find_freelancer_by_skill();
    });

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
});
