<div class="table-responsive pr">
    <div id="js_loader" class="loader2"></div>

    {!! Form::open(['route' => 'settings.company_profile_rules.store', 'files' => false, 'id' => 'js_js_rules_form', 'class' => 'js_form']) !!}
    <ul id="js_profiles_list" class="list-group terms max-w-700">
        @foreach($profiles as $k => $profile)
            <li class="js_list_item row-group-item">
                <h4>Profile: <strong>{!! $profile['name'] !!}</strong></h4>
                <div class="row">
                    @foreach($terms as $t => $term)
                        @php $checked = (isset($rules[$profile['id']][$term['id']]) && $rules[$profile['id']][$term['id']] == 1) ? true : false; @endphp
                        <div class="col-sm-6 col-md-4">
                            <label>{!! Form::checkbox('rules['.$profile['id'].']['.$term['id'].']', 1, $checked) !!} {!! $term['name'] !!}</label>
                        </div>
                    @endforeach
                </div>
            </li>
        @endforeach
    </ul>
    <div class="btn-group d-flex align-items-center">
        {!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
        <span id="js_response_message" class="ms-20"></span>
    </div>

    {!! Form::close() !!}
</div>
