<div data-repeater-item class="repeater-row clearfix">
    @if(isset($recipient))
        <input type="hidden" name="id" value="{{ $recipient->id }}" />
    @endif
    <input name="first_name" placeholder="First Name" class="form-control" value="{{ isset($recipient) ? $recipient->first_name : '' }}" />
    <input name="last_name" placeholder="Last Name" class="form-control" value="{{ isset($recipient) ? $recipient->last_name : '' }}" />
    <input name="email" placeholder="E-mail" class="form-control" value="{{ isset($recipient) ? $recipient->email : '' }}" />
    <input name="position" placeholder="Position" class="form-control" value="{{ isset($recipient) ? $recipient->position : '' }}" />
    <input name="department" placeholder="Department" class="form-control" value="{{ isset($recipient) ? $recipient->department : '' }}" />
    <i class="fa fa-times repeater-delete-row" data-repeater-delete></i>
</div>