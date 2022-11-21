<div class="table-responsive">
    <table class="table data-table custom-table">
        <thead>
            <tr class="filters">
                <th></th>
                <th></th>
                <th width="140"></th>
            </tr>
            <tr>
                <th>Name</th>
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
            ajax: "{{ url('landingTemplates/getPublicTable') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
