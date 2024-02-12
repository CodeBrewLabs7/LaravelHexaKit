$(document).ready(async function() {
    await InitDataTableWithoutServerSide('members');
})

$(document).on('click', '.AddMemberbtn', async function (e) {
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
            $('#addMembermodal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#addMemberForm #AddMemberBox').html(response.html);
		}
	} catch (error) {
        console.log(error);
	}
})

$(document).on('submit', '#addMemberForm', function (e) {
    e.preventDefault();
    let myForm = document.getElementById('addMemberForm');
    let formData = new FormData(myForm);
    let url = myForm?.getAttribute('action');
    AjaxFromSubmit('addMemberForm',formData, url,'POST',true);
})

$(document).on('click', '.edit_member', async function (e) {
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
            $('#EditMembermodal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#editMemberForm #EditMemberBox').html(response.html);
		}
	} catch (error) {
        console.log(error);
	}
})

$(document).on('submit', '#editMemberForm', function (e) {
    e.preventDefault();
    let id = $("input[name='id']").val();
    let myForm   = document.getElementById('editMemberForm');
    let formData = new FormData(myForm);
    let url = $.NotificationApp.ReplaceText(myForm?.getAttribute('action'), '{id}', id);
    AjaxFromSubmit('editMemberForm',formData, url,'POST',true);
})

$(document).on("click", ".delete_member", async function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    let url = $.NotificationApp.ReplaceText(delete_url, '{id}', id);
    const response = await deleteResource(url, id, 'Are you sure!','You won\'t be able to revert this!',true);

});
