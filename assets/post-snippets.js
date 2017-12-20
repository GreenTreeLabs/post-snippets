jQuery(document).ready(function ($) {
    /**
     * Loops through all snippets when page has loaded.
     */
    $("input[name$='_title']").each(function (index) {
        validateTitle($(this).prop('name'));
    });

    /**
     * Listens for title text input change events.
     */
    $("input[name$='_title']").change(function (e) {
        validateTitle(e.target.name);
    });

    /**
     * Listens for shortcode checkbox change events.
     */
    $("input[name$='_shortcode']").change(function (e) {
        validateTitle(e.target.name);
    });

    /**
     * Handle title validation and managing CSS classes.
     *
     * @param  string name
     *
     * @return void
     */
    function validateTitle(name) {
        // Extract the snippet index number
        var index = name.substring(0, name.indexOf('_'));

        var element = $("input[name$='" + index + "_title']");
        var title = $("input[name$='" + index + "_title']").val();
        $(element).removeClass('post-snippets-invalid');
        $(element).next().remove('p');

        if ($('#' + index + '_shortcode').prop('checked') && !isTitleValid(title)) {
            $(element).addClass('post-snippets-invalid');
            $(element).after("<span><em><font color='red'>" + post_snippets.invalid_shortcode + "</font></em></span>");
        }
    }

    /**
     * Determine if a title is shortcode valid.
     *
     * @param  string  title
     *
     * @return boolean
     */
    function isTitleValid(title) {
        return !Boolean(title.match(/[<>&/\[\]\x00-\x20]/gi));
    }

    /**
     * Bulk check/uncheck
     */
    $('.check-column input').on('change', function () {
       if( $(this).is(":checked") ){
            $('.post-snippets-item input[name^="checked"]').each(function(){
                $(this).attr('checked', true);
            });
       }else{
           $('.post-snippets-item input[name^="checked"]').each(function(){
               $(this).attr('checked', false);
           });
       }
    });
    /**
     * Update title real time
     */
    $('input.post-snippet-title').on('keyup', function () {
        var val = $(this).val();
        $(this).closest('.post-snippets-item').find('span.post-snippet-title').text(val);
    });

    /**
     * toggle folding
     */
    $('.toggle-post-snippets-data').on('click', function () {
        $(this).closest('.post-snippets-item').find('.post-snippets-data').slideToggle('fast');
        $(this).closest('.post-snippets-item').toggleClass('open');
        return false;
    });
    /**
     * Save title on the go
     */
    $('.save-title').on('click', function () {
        var wrap = $(this).closest('.post-snippets-item');
        var key = wrap.data('order');
        var title = wrap.find('input.post-snippet-title').val().trim();

        if (key === undefined) {
            alert('something went wrong try again');
            return false;
        }
        if ((title === undefined) || title === '') {
            alert('Invalid Title');
            return false;
        }
        var data = {
            'action': 'update_post_snippet_title',
            'key': key,
            'title': title
        };
        $.post(ajaxurl, data, function (res) {
            wrap.toggleClass('edit');
        });
        return false;
    });

    /**
     * toggle edit title mode
     */
    $('.post-snippets-toolbar .edit-title').on('click', function () {
        $(this).closest('.post-snippets-item').toggleClass('edit');
        return false;
    });
    /**
     * Make shortable list
     */
    $(".post-snippets").sortable({
        handle: ".handle",
        items: ".post-snippets-item",
        update: function () {
            var order = $('.post-snippets').sortable("toArray",{option:"sort", attribute:"data-order"});
            var data = {
                'action': 'update_post_snippets_order',
                'order': order
            };
            $.post(ajaxurl,data,function (res) {
                console.log(res);
            });
        }
    });
    $(".post-snippets").disableSelection();
});
