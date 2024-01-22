{{-- Add New Role Modal --}}
<div class="modal fade" id="addRolemodal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Add New Role') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="validationErrors"></div>
        <form id="addRoleForm" method="post" enctype="multipart/form-data" action="{{route('roles.store')}}">
            @csrf
            <div class="modal-body" id="AddRoleBox">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-info waves-effect waves-light addRoleSubmit">{{ __("Submit") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>
{{-- Edit Role --}}
<div class="modal fade" id="EditRolemodal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Role Information') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editRoleForm" method="post" enctype="multipart/form-data" action="{{ url('users/roles/{id}') }}">
            @csrf
            @method('PUT')
            <div class="modal-body" id="EditRoleBox">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-info waves-effect waves-light editRoleSubmit">{{ __("Update") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>

