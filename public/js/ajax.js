$(function () {
    return;
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });
    console.log('length', $('.ajax-form').length)
    $('.ajax-form').on('submit',function(e) {
        e.preventDefault(e);

        $this = $(this);
        if (typeof syncCkeditor == 'function')
            syncCkeditor();

        if (typeof window.formBuilder != 'undefined') {
            var attrs_json = window.formBuilder.actions.getData('json', true);
            $('<input />').attr('type', 'hidden')
                .attr('name', "attributes")
                .attr('value', attrs_json)
                .appendTo('.form-formbuilder')
            ;
            //data['attributes'] = attrs_json;
        }

        var data = new FormData(this);

        $.ajax({
            type:"POST",
            url: $(this).attr('action'),
            data: data,
            //dataType: 'json',
            cache: false,
            contentType: false,//'multipart/form-data',
            //contentType: "json",
            processData: false,
            success: function(resp) {
                updateHtml(resp);
                if (resp.redirect) {
                    window.location.href = resp.redirect;
                } else {
                    if (resp.msg)
                        notify(resp.msg);
                }

                if ($this.data('request-success')) {
                    var callback = window[$this.data('request-success')];
                    if(typeof callback === 'function') {
                        callback();
                    }
                }
            },
            error: function(resp) {
                console.log('resp.responseJSON', resp.responseJSON)
                notify(resp.responseJSON.msg, 'warning');
            }
        });

        return false;
    });

    $('.list-delete').click(function (e) {
        e.preventDefault(e);

        $.ajax({
            type: "DELETE",
            data: {
                "_token": $('meta[name="_token"]').attr('content')
            },
            url: $(this).data('href'),
            success: function(resp) {
                if (resp.redirect) {
                    window.location.href = resp.redirect;
                } else {
                    notify(resp.msg);
                }
            },
            error: function(resp) {
                notify(resp.msg, 'warning');
            }
        })
    });

    $('.link-delete').click(function (e) {
        e.preventDefault(e);
        $this = $(this);

        var options = {
            confirm: $this.data('request-confirm')
        };
        if (options.confirm && !requestOptions.handleConfirmMessage(options.confirm)) {
            return;
        }

        let data = paramToObj('data-request-data', $this.data('request-data'));

        data["_token"] = $('meta[name="_token"]').attr('content');

        $.ajax({
            type: "POST",
            data: data,
            url: $(this).attr('href'),
            success: function(resp) {
                if (resp.redirect) {
                    window.location.href = resp.redirect;
                } else {
                    notify(resp.msg);
                    $('.'+data.attr+' .media-thumb img, .'+data.attr+' .media-delete').remove();
                }
            },
            error: function(resp) {
                notify(resp.msg, 'warning');
            }
        })
    });

    $('.unfeature').click(function (e) {
        e.preventDefault(e);
        $this = $(this);

        var options = {
            confirm: $this.data('request-confirm')
        };
        if (options.confirm && !requestOptions.handleConfirmMessage(options.confirm)) {
            return
        }

        let data = paramToObj('data-request-data', $this.data('request-data'));

        data["_token"] = $('meta[name="_token"]').attr('content');

        $.ajax({
            type: "POST",
            data: data,
            url: $this.attr('href'),
            success: function(resp) {
                if (resp.redirect) {
                    window.location.href = resp.redirect;
                } else {
                    notify(resp.msg);
                }
            },
            error: function(resp) {
                notify(resp.msg, 'warning');
            }
        })
    });

    $('[name="property_type"]').change(function () {
        $this = $(this);
        let data = paramToObj('data-request-data', $this.data('request-data'));
        data["_token"] = $('meta[name="_token"]').attr('content');
        data["type_id"] = $this.val();

        $.post('/admin/properties/typefields', data, function (resp) {
            updateHtml(resp);
        });
    });
});

function paramToObj(name, value) {
    if (value === undefined) value = '';
    if (typeof value == 'object') return value;

    try {
        return JSON.parse(JSON.stringify(eval("({" + value + "})")));
    }
    catch (e) {
        throw new Error('Error parsing the '+name+' attribute value. '+e);
    }
}

function updateHtml(resp) {
    for (var key in resp) {
        if ($(key).length) {
            $(key).html(resp[key]);
        }
    }
}
