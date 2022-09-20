// https://wakirin.github.io/Lightpick/#configuration

$(document).ready(function () {
    initAjax();
    //initAjaxForm();

    $('.nav-tabs a').click(function () {
        $('.tab-pane').removeClass('show')
    });

    $('.bulk-form').on('submit', function (e) {
        e.preventDefault();
        initBulkImport($(this).attr('action'));
    });
    initEditor();

    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    initChartResults();
    initChartBrowsers();
    initChartClicks();
    initChartHome();

    initSidebarToggle();

    initLeaflet();

    //$('#scheduler').daterangepicker()
    initDatepicker();
    initTimepicker();


    $('#prettify').select2();

    $('[name*="send_to_landing"]').change(function(){
        toggleRedirectUrl($(this));
    });

    $('[name="scheduled_type"]').change(function(){
        toogleScheduledType($(this));
    });

    $('#email_template_id').change(function(){
        toggleShortLink($(this));
    });

    resetRedirectUrls();
    resetWithAttachment();
    resetScheduledTypes();
    initPresetUrlFromEmail();
});

function toggleRedirectUrl($_this){
    var $_parent = $_this.parents('form');

    if($_this.is(':checked')){
        $_parent.find('[name*="redirect_url"]').attr('disabled', 'disabled');
    }else{
        $_parent.find('[name*="redirect_url"]').removeAttr('disabled');
    }
}

function resetRedirectUrls(){
    $('[name*="send_to_landing"]').each(function(){
        if($(this).is(':checked')){
            $(this).parents('form').find('[name*="redirect_url"]').attr('disabled', 'disabled');
        }else{
            $(this).parents('form').find('[name*="redirect_url"]').removeAttr('disabled');
        }
    });
}

function toggleShortLink($_this) {
    var chcked_val = $_this.val();

    if ($_this.find("option[value='"+chcked_val+"']").text().includes('With attachment')) {
        $('#is_short').prop('checked', false).attr('disabled', 'disabled');
    } else {
        $('#is_short').prop('checked', false).removeAttr('disabled');
    }
}

function resetWithAttachment(){
    if ($('#email_template_id').length > 0) {
        if ($("#email_template_id option:selected").text().includes('With attachment')) {
            $('#is_short').prop('checked', false).attr('disabled', 'disabled');
        }
    }
}

function initDatepicker() {
    var pickerEljqs = $('.datepicker');
    if (pickerEljqs.length) {
        pickerEljqs.each(function () {
            var pickerEljq = $(this);
            //var pickerEl = document.getElementById('datepicker');
            var singleDate = true;
            if(pickerEljq.hasClass('daterange')) {
                singleDate = false;
            }

            var picker = new Lightpick({
                field: this,
                singleDate: singleDate,
                minDate: moment(),
            });
            if (false == singleDate) {
                picker.setDateRange(pickerEljq.data('start'), pickerEljq.data('end'));
            } else {
                picker.setDate(pickerEljq.data('val'));
            }
        })
    }
}

function initTimepicker() {
    $(".time_start").timepicki({show_meridian:false,min_hour_value:0,
        max_hour_value:23,});
    $(".time_end").timepicki({show_meridian:false,min_hour_value:0,
        max_hour_value:23,});
}

function initBulkImport(action) {
    $.ajax({
        // Your server script to process the upload
        url: action,
        type: 'POST',

        // Form data
        data: new FormData($('form')[0]),

        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,

        // Custom XMLHttpRequest
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                // For handling the progress of the upload
                myXhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        $('progress').attr({
                            value: e.loaded,
                            max: e.total,
                        });
                    }
                } , false);
            }
            return myXhr;
        },
        success: function (resp) {
            console.log('resp', resp)
            var $repeater = $('.repeater').repeater();
            console.log('$repeater', $repeater)
            var list = {'recipients_attrs': resp};
            $repeater.setList(list);
            var values = $repeater.repeaterVal();
            console.log('values', values)
        }
    });
}

function clearInputFile(f){
    if(f.value){
        try{
            f.value = ''; //for IE11, latest Chrome/Firefox/Opera...
        }catch(err){ }
        if(f.value){ //for IE5 ~ IE10
            var form = document.createElement('form'),
                parentNode = f.parentNode, ref = f.nextSibling;
            form.appendChild(f);
            form.reset();
            parentNode.insertBefore(f,ref);
        }
    }
}

function initEditor() {
    //console.log($('#editor').length)
    if ($('#editor').length) {
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],

            //['clean']                                         // remove formatting button
        ];
        var Clipboard = Quill.import('modules/clipboard');
        Quill.register('modules/clipboard', Clipboard, true);

        var quill = window.quill = new Quill('#editor', {
            modules: {
                toolbar: toolbarOptions,
                'history': {          // Enable with custom configurations
                    'delay': 2500,
                    'userOnly': true
                }
            },
            theme: 'snow'
        });
        quill.on('text-change', function(delta, oldDelta, source) {
            //console.log(quill.getText(), quill.container.firstChild.innerHTML)
            if (source == 'api') {
                console.log("An API call triggered this change.");
            } else if (source == 'user') {
                console.log("A user action triggered this change.");
            }
            $('#editor-html').val(quill.container.firstChild.innerHTML);
            //quill.clipboard.dangerouslyPasteHTML(5, '&nbsp;<b>World</b>');
        });
        $('#editor-html').val(quill.container.firstChild.innerHTML);
    }
}

function initAjax() {
    $('[data-request-url]').click(function(){
        var $loader_result_tag =$('body').find('.js_ajax_message');
        var params = {};
        var names = $(this).data('request-data').split(',');
		console.log('names', names);

        $loader_result_tag.text('Sending request...').fadeIn(100);

        for(var i = 0; i < names.length; i++){
            var name = names[i];
            var elem = $('#'+name);
            if(elem.attr('type') == 'checkbox'){
                if(elem.is(':checked'))
                    params[name] = 1;
                else
                    params[name] = 0;
            }else{
                params[name] = elem.val();
            }
        }
        console.log('params', params);
        axios
            .post($(this).data('request-url'), params, {})
            .then(function(resp){
                $loader_result_tag.text('SUCCESS!').delay(2000).fadeOut(500);
                console.log('SUCCESS!!');
                console.log('resp', resp);
                if(resp.data.redirect){
                    window.location.href = resp.data.redirect;
                }else if(resp.data.msg){
                    notify(resp.data.msg);
                }else if(resp.data.rel){
                    window.location.reload();
                }
            })
            .catch(function(){
                $loader_result_tag.text('FAILURE!').delay(2000).fadeOut(500);
                console.log('FAILURE!!');
            });
    });
}

function initAjaxForm() {
    $('.ajax-form').submit(function (e) {
        e.preventDefault();
        axios.patch('http://174.138.5.246/groups/4');
        return false;
     //   console.log($(this)[0])
   //     var formData = new FormData($(this)[0]);
        var data = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
         var formData = new FormData(document.querySelector('.ajax-form'))
//       //  console.log('data', data)
//      //   var params = {};
// console.log('formData', formData)
        var url = $(this).attr('action');
        console.log('url', url)
        // axios.patch('http://174.138.5.246/groups/4', {})
        // .then(function(resp){
        //     console.log('SUCCESS!!');
        //     console.log('resp', resp)
        //
        // })
        // .catch(function(){
        //     console.log('FAILURE!!');
        //
        // });

        $.ajax({
            // Your server script to process the upload
            url: '/groups/4',
            type: 'PATCH',

            // Form data
            data: formData,

            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            dataType: 'json',
            processData: false,

            success: function (resp) {
                console.log('resp', resp)
            }
        });

        return false;
    });
}

function initChartResults() {
    if ($('#chartResults').length == 0)
        return;

    var pieChartCanvas = $('#chartResults').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
        {
            value    : $('#chartResults').data('sent'),
            color    : '#f56954',
            highlight: '#f56954',
            label    : 'Sent'
        },
        {
            value    : $('#chartResults').data('open'),
            color    : '#00a65a',
            highlight: '#00a65a',
            label    : 'Opened'
        },
        {
            value    : $('#chartResults').data('click'),
            color    : '#f39c12',
            highlight: '#f39c12',
            label    : 'Clicked'
        },
        {
            value    : $('#chartResults').data('report'),
            color    : '#0600f3',
            highlight: '#0600f3',
            label    : 'Reported'
        }
    ];
    var pieOptions     = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke    : true,
        //String - The colour of each segment stroke
        segmentStrokeColor   : '#fff',
        //Number - The width of each segment stroke
        segmentStrokeWidth   : 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps       : 100,
        //String - Animation easing effect
        animationEasing      : 'easeOutBounce',
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate        : true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale         : false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive           : true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio  : true,
        //String - A legend template
        legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)
}

function initChartBrowsers() {
    if ($('#chartBrowsers').length == 0)
        return;
    var data = $('#chartBrowsers').data('data');

    var pieChartCanvas = $('#chartBrowsers').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
        {
            value    : 0,
            color    : '#f56954',
            highlight: '#f56954',
            label    : 'Chrome',
            user_agent: "chrome"
        },
        {
            value    : 0,
            color    : '#00a65a',
            highlight: '#00a65a',
            label    : 'Firefox',
            user_agent: "firefox"
        },
        {
            value    : 0,
            color    : '#008d4c',
            highlight: '#008d4c',
            label    : 'Opera',
            user_agent: "opera"
        },
        {
            value    : 0,
            color    : '#f39c12',
            highlight: '#f39c12',
            label    : 'IE',
            user_agent: "ie"
        },
        {
            value    : 0,
            color    : '#3c8dbc',
            highlight: '#3c8dbc',
            label    : 'Safari',
            user_agent: "safari"
        },
        {
            value    : 0,
            color    : '#CC44BC',
            highlight: '#CC44BC',
            label    : 'Other',
            user_agent: "other"
        }
    ];

    for (var i in PieData) {
        for (var k in data) {
            if (data[k].user_agent == PieData[i].user_agent) {
                PieData[i].value = data[k].total;
            }
        }
    }

    var pieOptions     = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke    : true,
        //String - The colour of each segment stroke
        segmentStrokeColor   : '#fff',
        //Number - The width of each segment stroke
        segmentStrokeWidth   : 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps       : 100,
        //String - Animation easing effect
        animationEasing      : 'easeOutBounce',
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate        : true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale         : false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive           : true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio  : true,
        //String - A legend template
        legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)
}

function initChartHome() {
    if ($('#chartHome').length == 0)
        return;

    var labels_str = $('#chartHome').data('labels');
    var labels = labels_str.split(',');
    var short_labels_str = $('#chartHome').data('short-labels');
    var short_labels = short_labels_str.split(',');
    //console.log(tooltip_labels);

    var data_sents = $('#chartHome').data('data-sents');
    var data_opens = $('#chartHome').data('data-opens');
    var data_clicks = $('#chartHome').data('data-clicks');
    var data_fake_auth = $('#chartHome').data('data-fakeauth');
    var data_attachments = $('#chartHome').data('data-attachments');
    var data_smishs = $('#chartHome').data('data-smishs');
    var data_reports = $('#chartHome').data('data-reports');

    var lineChartCanvas = $('#chartHome').get(0).getContext('2d');
    var lineChart       = new Chart(lineChartCanvas);
    var lineData        = {
        tooltip_labels: labels,
        labels :    short_labels,
        datasets :  [
            {
                label : "Sent",
                fillColor : "rgba(1,191,238,0.2)",
                strokeColor : "rgba(1,191,238,1)",
                pointColor : "rgba(1,191,238,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(1,191,238,1)",
                data : data_sents
            },
            {
                label : "Opened",
                fillColor : "rgba(243,156,18,0.2)",
                strokeColor : "rgba(243,156,18,1)",
                pointColor : "rgba(243,156,18,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(243,156,18,1)",
                data : data_opens
            },
            {
                label : "Click(s)",
                fillColor : "rgba(221,75,57,0.2)",
                strokeColor : "rgba(221,75,57,1)",
                pointColor : "rgba(221,75,57,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(221,75,57,1)",
                data : data_clicks
            },
            {
                label : "Attachments",
                fillColor : "rgba(97,8,130,0.2)",
                strokeColor : "rgba(97,8,130,1)",
                pointColor : "rgba(97,8,130,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(97,8,130)",
                data : data_attachments
            },
            {
                label : "Data entry",
                fillColor : "rgba(255,193,7,0.2)",
                strokeColor : "rgba(255,193,7,1)",
                pointColor : "rgba(255,193,7,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(255,193,7)",
                data : data_fake_auth
            },
            {
                label : "Smishing",
                fillColor : "rgba(136,29,2,0.2)",
                strokeColor : "rgba(136,29,2,1)",
                pointColor : "rgba(136,29,2,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(136,29,2)",
                data : data_smishs
            },
            {
                label : "Reported",
                fillColor : "rgba(1,165,89,0.2)",
                strokeColor : "rgba(1,165,89,1)",
                pointColor : "rgba(1,165,89,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(1,165,89,1)",
                data : data_reports
            }
        ]
    };

    var options = {
        // animation: true,
        // animationSteps: 100,
        // animationEasing: "easeOutQuart",
        // scaleFontSize: 16,
        // responsive: true,
        showTooltips: true,
        multiTooltipTemplate: "<%= value %>% - <%= datasetLabel %>",
        // scaleShowGridLines : false,
        // bezierCurve : false,
        // pointDotRadius : 5,
    };

    lineChart.Line(lineData, options);

}


function initChartClicks() {
    if ($('#barChart').length == 0)
        return;

    var data = $('#barChart').data('data');
    var labels_str = $('#barChart').data('labels');
    var barValueSpacing = $('#barChart').data('spacing');
    if (!barValueSpacing)
        barValueSpacing = 5;
    var labels = labels_str.split(',');

    var areaChartData = {
        labels  : labels,
        datasets: [
            {
                label               : 'Electronics',
                fillColor           : '#9c9c9c',
                strokeColor         : 'rgba(210, 214, 222, 1)',
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : data
            }
        ]
    };

    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData

    var barChartOptions                  = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero        : true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines      : true,
        //String - Colour of the grid lines
        scaleGridLineColor      : 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth      : 0.5,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines  : true,
        //Boolean - If there is a stroke on each bar
        barShowStroke           : true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth          : 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing         : barValueSpacing,
        //Number - Spacing between data sets within X values
        barDatasetSpacing       : 1,
        //String - A legend template
        legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        //Boolean - whether to make the chart responsive
        responsive              : true,
        maintainAspectRatio     : true
    };

    barChartOptions.datasetFill = false;
    barChart.Bar(barChartData, barChartOptions);

    var ctx = document.getElementById("barChart").getContext("2d");
    window.myObjBar = new Chart(ctx).Bar(barChartData, {
        responsive : true
    });

    if (myObjBar.datasets[0].bars[0]) {
        myObjBar.datasets[0].bars[0].fillColor = "#01bfee";
    }
    if (myObjBar.datasets[0].bars[1]) {
        myObjBar.datasets[0].bars[1].fillColor = "#f39c12";
    }
    if (myObjBar.datasets[0].bars[2]) {
        myObjBar.datasets[0].bars[2].fillColor = "#dd4b39";
    }
    if (myObjBar.datasets[0].bars[3]) {
        myObjBar.datasets[0].bars[3].fillColor = "#881d02";
    }
    if (myObjBar.datasets[0].bars[4]) {
        myObjBar.datasets[0].bars[4].fillColor = "#ffc107";
    }
    if (myObjBar.datasets[0].bars[5]) {
        myObjBar.datasets[0].bars[5].fillColor = "#610882";
    }
    if (myObjBar.datasets[0].bars[6]) {
        myObjBar.datasets[0].bars[6].fillColor = "#01a559";
    }
    myObjBar.update();
}

function initSidebarToggle() {
    $('.sidebar-toggle').click(function () {
        if ($('#sidebar-wrapper').hasClass('visible')) {
            $('#sidebar-wrapper').removeClass('visible');
        } else {
            $('#sidebar-wrapper').addClass('visible');
        }
    });
}

function initLeaflet() {
    if (!$('#mapid').length)
        return;

    var mymap = L.map('mapid', {
        //center: [51.505, -0.09],
            zoom: 5
    }).setView([37.8, -96], 4);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        tileSize: 512,
        zoomOffset: -1,
        id: 'mapbox/streets-v11',
        accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
    }).addTo(mymap);

    var coords = $('#mapid').data('markers');
    var bounds = [];
    if (coords) {
        for (var i = 0; i < coords.length; i++) {
            var coord = [parseFloat(coords[i].lat), parseFloat(coords[i].lng)];
            bounds.push(coord);

            var myIcon = L.icon({
                iconUrl: '/img/'+coords[i].type+'.png',
                iconSize: [38, 38],
                //iconAnchor: [22, 94],
                popupAnchor: [-3, -76],
                //shadowUrl: 'my-icon-shadow.png',
                shadowSize: [68, 95],
                shadowAnchor: [22, 94]
            });
            console.log(coord);

            var marker = L.marker(coord, {icon: myIcon}).addTo(mymap);
        }
    }

    mymap.fitBounds(bounds, {
        maxZoom: 5
    });
}

function toogleTextType() {
    var type = $('[name="text_type"]:checked').val();

    $('.text_type').hide();
    $('.text_type-'+type).show();
}

function toogleScheduledType($_this){
    var $_parent = $_this.parents('form'),
        type = ~~$_this.val();

    //console.log('type', type)

    if(type == 1)
        $_parent.find('.scheduled_type').show();
    else
        $_parent.find('.scheduled_type').hide();

}

function resetScheduledTypes(){
    $('[name="scheduled_type"]:checked').each(function(){
        var type = $(this).val();
        if(parseInt(type))
            $(this).parents('form').find('.scheduled_type').show();
        else
            $(this).parents('form').find('.scheduled_type').hide();
    });
}

function initPresetUrlFromEmail() {
    if ($('[name="url"]').val() == '')
    $('[name="email"]').keyup(function () {
        var val = $(this).val();console.log('val',val)
        var parts = val.split('@');
        console.log('parts',parts)
        if (parts.length == 2) {
            $('[name="url"]').val(parts[1]);
        }
    });
}
