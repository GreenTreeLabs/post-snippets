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

    $(".snippet-delete").click(function (e) {
        e.preventDefault();
        var $el = $(this);
        var id = $(this).data("target");

        if($(this).data("deleting"))
            return;

        var key = $(this).data("key");
        var data = {
            'action': 'delete_post_snippet',
            'key': key,
            'delete_snippet': $("#delete_snippet").val()
        };
        $(this).data("deleting", true);
        $("#delete-confirm strong").text($("#" + id + "-title").text());

        $( "#delete-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              Cancel: function() {
                $(this).data("deleting", false);
                $( this ).dialog( "close" );
              },
              "Delete snippet": function() {
                $( this ).dialog( "close" );
                $.post(ajaxurl, data, function (res) {
                    if(res.success){
                        $el.parents(".post-snippets-item").remove();
                    }else{
                     alert(res.data);
                    }
                });
              },
            }
        });
	});

	$("[data-ps-download]").click(function (e) {
		e.preventDefault();

		var data = {
            'action': 'sync_list',
            'sync_down': $("#sync_down").val()
		};
		$("#sp-loading").show();
		$.post(ajaxurl, data, function (res) {
			$("#sp-loading").hide();

			if(res.data.data.length == 0) {
				$("#sync-down-list .message").show().text("You have no saved snippets yet");
			} else {
				$("#sync-down-list .message").hide();
			}

			$("#sync-down-list table tbody").empty();
			$.each(res.data.data, function () {
				var $tr = $("<tr><td></td><td></td><td></td><td></td><td></td></tr>");
				$tr.find("td").eq(0).append("<input type='checkbox' value='"+ this.id +"' />");
				$tr.find("td").eq(1).append(this.title);
				$tr.find("td").eq(2).append(this.description ? this.description : "-");
				$tr.find("td").eq(3).append(this.vars);
				$tr.find("td").eq(4).append("<textarea readonly>"+ this.snippet +"</textarea>")
				$("#sync-down-list table tbody").append($tr);

				$tr.find("input").click(function() {
					var count = $("#sync-down-list table tbody input:checked").length;
					$(".ui-dialog-buttonpane button").last().text("Add ("+ count +") selected");
				});
			});

			$("#sync-down-list [data-toggle-all]").off("click").on("click", function () {
				var checked = this.checked;
				$("#sync-down-list table tbody input").each(function () {
					this.checked = checked;
				})

				var count = $("#sync-down-list table tbody input:checked").length;
				$(".ui-dialog-buttonpane button").last().text("Add ("+ count +") selected");
			});

			$("#sync-down-list").dialog({
				resizable: false,
				height: "auto",
				width: 800,
				modal: true,
				buttons: {
					Cancel: function() {
					  $( this ).dialog( "close" );
					},
					"Add (0) selected": function () {
						var selected = [];
						$("#sync-down-list table tbody input:checked").each(function () {
							selected.push(this.value);
						})

						if(selected.length == 0) {
							return;
						}

						var data = {
							'action': 'sync',
							'ids': selected.join(','),
							'sync_up': $("#sync_up").val()
						};

						$( this ).dialog( "close" );
						$("#sp-loading").show();
						$.post(ajaxurl, data, function (res) {
							$("#sp-loading").hide();
							if(res.success){
								$("#sync-success").dialog({
									resizable: false,
									height: "auto",
									modal: true,
									closeOnEscape: false,
									buttons: {
										"Ok": function() {
											location.href = location.href + "&reload=" + Math.random();
										}
									},
									close: function() {
										location.href = location.href + "&reload=" + Math.random();
									}
								})
							}
						});
					}
				}
			});
		});
	});

	$("[data-sync-up]").click(function (e) {
		e.preventDefault();

		var key = $(this).data("key");
		var data = {
            'action': 'sync_up',
			'key': key,
			'update': 0,
            'sync_up': $("#sync_up").val()
		};

		var id = $(this).data("target");
		$("#sync-up-confirm strong").text($("#" + id + "-title").text());

		$( "#sync-up-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              Cancel: function() {
                $( this ).dialog( "close" );
              },
              "Save snippet": function() {
				$( this ).dialog( "close" );
				$("#sp-loading").show();
                $.post(ajaxurl, data, function (res) {
					$("#sp-loading").hide();
                    if(res.success){
                        $("#sync-up-success").dialog({
							resizable: false,
							height: "auto",
							width: 300,
							modal: true
						});
                    } else{
						if(res.data.code == "2") {
							var snippet = JSON.parse(res.data.message);
							snippet.options = JSON.parse(snippet.options);

							$("#sync-update-confirm [data-title]").text(snippet.title);
							$("#sync-update-confirm [data-description]").text(snippet.description);
							$("#sync-update-confirm textarea").val(snippet.snippet);
							$("#sync-update-confirm [data-vars]").val(snippet.vars);

							for(var k in snippet.options) {
								if(snippet.options[k])
								$("#sync-update-confirm [data-options]").append("<i class='dashicons dashicons-yes'></i> " + k);
							}
							//$("#sync-update-confirm [data-options]").text(snippet.options);

							$("#sync-update-confirm").dialog({
								resizable: false,
								height: "auto",
								width: 600,
								modal: true,
								buttons: {
									Cancel: function() {
									  $( this ).dialog( "close" );
									},
									Update: function () {
										$( this ).dialog( "close" );
										$("#sp-loading").show();
										data.update = 1;
										$.post(ajaxurl, data, function (res) {
											$("#sp-loading").hide();
											if(res.success){
												$("#sync-up-success").dialog({
													resizable: false,
													height: "auto",
													width: 300,
													modal: true
												});
											} else {
												$("#sync-up-error [data-error]").html("code: " + res.data.code + "<br>message: " + res.data.message);
											}
										});
									}
								}
							});
						} else {
							$("#sync-up-error [data-error]").html("code: " + res.data.code + "<br>message: " + res.data.message);
							$("#sync-up-error").dialog({
								resizable: false,
								height: "auto",
								width: 400,
								modal: true
							});
						}
                    }
                });
              },
            }
        });
	});
});
