<div class="table-responsive">
    <table class="table data-table custom-table">
        <thead>
            <tr class="filters">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th width="140"></th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Language</th>
                <th>Module</th>
                <th>Type</th>
                <th>Last Modified</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function(){

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            ajax: "{{ url('trainingTemplates/getPublicTable') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'subject', name: 'subject'},
                {data: 'language', name: 'language'},
                {data: 'module', name: 'module'},
                {data: 'type', name: 'type'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            initComplete: function(){
	            var select = $('<select style="width: 100%;"><option value=""></option></select>');

                this.api().columns([2]).every(function(index){
                    var column = this;

	                select
                        .appendTo($('.dataTable thead .filters th:eq('+index+')').empty())
                        .on('change', function(){
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column
                                .search(val ? '^'+val+'$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j){
                        if(d){
                            select.append('<option value="'+d+'">'+d+'</option>');
                        }
                    });


                });
            },
            "fnDrawCallback": function(oSettings){
                updateLangsFlags();
            },
        });

        var updateLangsFlags = function(){
            table.rows().every(function(index){
                console.log(index);
                var lang_code = this.data().DT_RowAttr['data-lang'];
                if(lang_code != ''){
                    var $td = $('.dataTable tbody tr:eq(' + index + ') td:eq(2)');
                    var lang_name = $td.text();
                    var $img = $('<img>');
                    $img.attr('src', '/img/pmflags/' + lang_code + '.png').attr('class', 'lang-flag');
                    $td.html($img).append(lang_name);
                }
            });
        };

    });
</script>
