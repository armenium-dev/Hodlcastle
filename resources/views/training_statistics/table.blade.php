<div>
    <table class="table data-table table-responsive">
        <thead>
            <tr>
                <th>Recipient</th>
                <th>Email</th>
                <th>Company</th>
                <th>Start</th>
                <th>Finish</th>
                <th>Time spent</th>
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
            ajax: "{{ url('trainingStatistic/getTable') }}",
            columns: [
                {data: 'recipient', name: 'recipient'},
                {data: 'recipient_email', name: 'recipient_email'},
                {data: 'company', name: 'company'},
                {data: 'start_training', name: 'start_training'},
                {data: 'finish_training', name: 'finish_training'},
                {data: 'time', name: 'time'},
            ]
        });

    });
</script>