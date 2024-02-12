$(document).ready(async function() {
    await InitDataTableWithoutServerSide('roles');
})

$(document).on('click', '.AddRolebtn', async function (e) {
    e.preventDefault();
    const options = {
		type: 'get',
		data: {},
		dataType: 'json',
		url: create_url,
		headers: { Accept: "application/json" },
		beforeSend: function () {
			$.LoadingOverlay("show");
		},
		complete: function () {
			$.LoadingOverlay("hide");
		}
    };
    try {
		const response = await ajaxRequest(options);
		if (response.status === true) {
            $('#addRolemodal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#addRoleForm #AddRoleBox').html(response.html);
		}
	} catch (error) {
        console.log(error);
	}
})

$(document).on('submit', '#addRoleForm', function (e) {
    e.preventDefault();
    let myForm = document.getElementById('addRoleForm');
    let formData = new FormData(myForm);
    let url = myForm?.getAttribute('action');
    AjaxFromSubmit('addRoleForm',formData, url,'POST',true);
})

$(document).on('click', '.edit_role', async function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    const url = $.NotificationApp.ReplaceText(edit_url, '{id}', id);
    const options = {
		type: 'get',
		data: {},
		dataType: 'json',
		url: url,
		headers: { Accept: "application/json" },
		beforeSend: function () {
			$.LoadingOverlay("show");
		},
		complete: function () {
			$.LoadingOverlay("hide");
		}
    };
    try {
		const response = await ajaxRequest(options);
		if (response.status === true) {
            $('#EditRolemodal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#editRoleForm #EditRoleBox').html(response.html);
		}
	} catch (error) {
        console.log(error);
	}
})

$(document).on('submit', '#editRoleForm', function (e) {
    e.preventDefault();
    let id = $("input[name='id']").val();
    let myForm   = document.getElementById('editRoleForm');
    let formData = new FormData(myForm);
    let url = $.NotificationApp.ReplaceText(myForm?.getAttribute('action'), '{id}', id);
    AjaxFromSubmit('editRoleForm',formData, url,'POST',true);
})

$(document).on("click", ".delete_role", async function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    let url = $.NotificationApp.ReplaceText(delete_url, '{id}', id);
    const response = await deleteResource(url, id, 'Are you sure!','You won\'t be able to revert this!',true);

});
