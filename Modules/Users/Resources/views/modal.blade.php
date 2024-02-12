{{-- Add New Member Modal --}}
<div class="modal fade" id="addMembermodal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Add New Member') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="validationErrors"></div>
        <form id="addMemberForm" method="post" enctype="multipart/form-data" action="{{route('users.store')}}">
            @csrf
            <div class="modal-body" id="AddMemberBox">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-info waves-effect waves-light addMemberSubmit">{{ __("Submit") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>
{{-- Edit User --}}
<div class="modal fade" id="EditMembermodal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit User Information') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editMemberForm" method="post" enctype="multipart/form-data" action="{{ url('users/users/{id}') }}">
            @csrf
            @method('PUT')
            <div class="modal-body" id="EditMemberBox">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-info waves-effect waves-light editMemberSubmit">{{ __("Update") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>

