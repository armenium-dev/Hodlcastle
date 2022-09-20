<div class="table-responsive">
    <table class="table" id="courses-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Language</th>
                <th>Module ID</th>
                <th>Course ID</th>
                <th>Public</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($courses as $course)
            <tr>
                <td>{!! $course->name !!}</td>
                <td>{!! $course->language->name !!}</td>
                <td>{!! $course->module->id !!}</td>
                <td>{!! $course->id !!}</td>
                <td>{!! $course->public ? 'yes' : 'no' !!}</td>
                <td>
                    {!! Form::open(['route' => ['courses.destroy', $course->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('courses.show', [$course->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('courses.edit', [$course->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>