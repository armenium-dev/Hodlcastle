<div class="table-responsive pr">
    <div id="js_loader" class="loader2"></div>

    {!! Form::open(['route' => 'settings.company_profile_terms.store', 'files' => false, 'id' => 'js_terms_form', 'class' => 'js_form']) !!}
    <ul id="js_terms_list" class="list-group terms max-w-700">
        @foreach($terms as $k => $term)
            <li class="js_list_item list-group-item">
                {!! Form::hidden('ids[]', $term['id']) !!}
                <div class="col flex-1">{!! Form::text('names[]', $term['name'], ['class' => 'form-control', 'placeholder' => 'Section name']) !!}</div>
                <div class="col flex-1">{!! Form::text('slugs[]', $term['slug'], ['class' => 'form-control', 'placeholder' => 'Section slug']) !!}</div>
                <div class="col"><a class="js_delete_row btn btn-danger" href="#" data-parent="#js_terms_list" data-type="term"><i class="fa fa-trash"></i> Delete</a></div>
            </li>
        @endforeach
    </ul>
    <div class="btn-group d-flex align-items-center">
        {!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
        <a class="js_add_row btn btn-default" href="#" data-target="#js_terms_list"><i class="fa fa-plus"></i> Add new</a>
        <span id="js_response_message" class="ms-20"></span>
    </div>

    {!! Form::close() !!}
</div>

