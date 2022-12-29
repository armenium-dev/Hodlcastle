<div class="table-responsive pr">
    <div id="js_loader" class="loader2"></div>

    {!! Form::open(['route' => 'settings.company_profiles.store', 'files' => false, 'id' => 'js_js_profiles_form', 'class' => 'js_form']) !!}
    <ul id="js_profiles_list" class="list-group terms max-w-500">
        @foreach($profiles as $k => $profile)
            <li class="js_list_item">
                <div class="list-group-item">
                    {!! Form::hidden('ids[]', $profile['id']) !!}
                    <div class="col flex-1">{!! Form::text('names[]', $profile['name'], ['class' => 'form-control', 'placeholder' => 'Profile name']) !!}</div>
                    <div class="col"><a class="js_delete_row btn btn-danger" href="#" data-parent="#js_profiles_list" data-type="profile"><i class="fa fa-trash"></i> Delete</a></div>
                </div>
                {{--
                <div class="row">
                    @foreach($terms as $t => $term)
                        @php $checked = isset($roles[$profile['id']][$term['id']]) ? true : false; @endphp
                        <div class="col-sm-6 col-md-4">
                            <label>{!! Form::checkbox('terms['.$k.']['.$t.']', $term['id'], $checked) !!} {!! $term['name'] !!}</label>
                        </div>
                    @endforeach
                </div>
                --}}
            </li>
        @endforeach
    </ul>
    <div class="btn-group d-flex align-items-center">
        {!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
        <a class="js_add_row btn btn-default" href="#" data-target="#js_profiles_list"><i class="fa fa-plus"></i> Add new</a>
        <span id="js_response_message" class="ms-20"></span>
    </div>

    {!! Form::close() !!}
</div>
