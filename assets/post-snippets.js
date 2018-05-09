jQuery(document).ready(function ($) {
    /**
     * Check if there are any snippets
     */
    if (document.getElementsByClassName("post-snippets-item").length == 0) {
        $(this).find(".post-snippets-list-empty").show();
        $(this).find(".post-snippets-buttons-bottom").hide();
        $(this).find(".post-snippets-table .expand-all").hide();
        $(this).find(".post-snippets-table .collapse-all").hide();

        $( 'input[name=update-snippets]' ).attr('class', 'button-secondary');
        $( 'input[name=add-snippet]' ).attr('class', 'button-primary');

        (function pulse(){
            $( 'input[name=add-snippet]' ).first().delay(200).animate({'opacity':1}).delay(2000).animate({'opacity':0},pulse);
        })();

    }

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
        if ($(this).is(":checked")) {
            $('.post-snippets-item input[name^="checked"]').each(function () {
                $(this).attr('checked', true);
            });
        } else {
            $('.post-snippets-item input[name^="checked"]').each(function () {
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
        var item = $(this).closest('.post-snippets-item');
        var openItems = getFromLocalStorage();
        var key = parseInt(item.data('order'));
        if (_.contains(openItems, key)) {
            setInLocalStorage(_.without(openItems, key));
        } else {
            setInLocalStorage(_.union(openItems, [key]));
        }
        $(this).closest('.post-snippets-item').toggleClass('open');

        // $(this).closest('.post-snippets-item').toggleClass('open');
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
            if(res.success){
                wrap.find('input.post-snippet-title').val(res.data);
                wrap.find('span.post-snippet-title').text(res.data);
                wrap.toggleClass('edit');
            }else{
             alert(res.data);
            }
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
     * Set value in localStorage
     * @param value
     * @param name
     */
    function setInLocalStorage(value, name) {
        var optionName = name || 'openSnippets';
        localStorage.setItem(optionName, JSON.stringify(value));
    }

    /**
     * get saved value
     * @param name
     * @returns {Array}
     */
    function getFromLocalStorage(name) {
        var optionName = name || 'openSnippets';
        var savedValue = localStorage.getItem(optionName);
        if (savedValue !== null) {
            return JSON.parse(savedValue);
        }
        return [];
    }

    /**
     * handle open close
     */
    $('.post-snippets .post-snippets-item').each(function () {
        var key = $(this).data('order');
        var openSnippets = getFromLocalStorage();
        if (_.contains(openSnippets, key)) {
            $(this).addClass('open');
        }
    });

    /**
     * Handle Expand Collapse
     */
    $('.expand-collapse a').on('click', function () {
        var isExpand = !$('.expand-collapse').hasClass('expanded');
        if (isExpand) {
            $('.post-snippets-item').not('.open').find('.toggle-post-snippets-data').trigger('click');
        } else {
            $('.post-snippets-item.open').find('.toggle-post-snippets-data').trigger('click');
        }
        $('.expand-collapse').toggleClass('expanded');
        return false;
    });


    $('form.post-snippets-wrap').on('submit', function (e) {
        var list_of_values = [];

        $('input.post-snippet-title').each(function (key, element) {
            var val = $(element).val().trim();
            if( $.inArray(val, list_of_values)){
                list_of_values.push(val);
            }else{
                alert('Duplicate title is not allowed. Please use different title for each snippets.');
                $(element).closest('.post-snippets-item').css('border', '1px solid red');
                e.preventDefault();
               return false;
            }
        });

        return true ;
    });

});
