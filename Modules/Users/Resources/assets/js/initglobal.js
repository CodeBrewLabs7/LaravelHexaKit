$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

class NotificationApp {

    ReplaceText =function(originalText,replaceText,newText) {
        var newReplaceString = originalText.replace(replaceText, newText);
        return newReplaceString;
    }

    ReloadUrl = function(timeSet) {
        setTimeout(function() {
            window.location.reload();
          }, timeSet);
    }

    successSweetAlertWithReload =function(title,text,icon) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
          }).then((result) => {
             if(result){
               location.reload();
             }
          })
    }
}

$.NotificationApp = new NotificationApp();

window.ajaxRequest = function (options) {
	return new Promise((resolve, reject) => {
		$.ajax({
			...options,
			success: function (response) {
				resolve(response);
			},
			error: function (xhr, status, error) {
				reject({ xhr, status, error });
			}
		});
	});
};


window.AjaxFromSubmit = async function (form_id, formData, url, method, reloadUrl=false) {
	const options = {
		type: method ?? "POST",
		data: formData,
		contentType: false,
		processData: false,
		url: url,
		headers: { Accept: "application/json" },
        dataType:'json',
		beforeSend: function () {
			$.LoadingOverlay("show");
		},
		complete: function () {
			$.LoadingOverlay("hide");
		}
	};

	try {
		const response = await ajaxRequest(options);
		if (response.status) {

            if(reloadUrl) {
                 $.NotificationApp.successSweetAlertWithReload('Success!', response.message, 'success')
            }
		}
	} catch (error) {
        var errors = error.xhr.responseJSON.errors;
        $.each(errors, function (key, value) {
            displayErrors([value[0]]);
            return false;
        });
	}
};


window.deleteResource = async function (route, resourceId,title,text,reloadUrl) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: route,
                data: {'_method' : 'DELETE'},
                dataType:'json',
                success: function (data) {
                    if(reloadUrl) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Yes'
                          }).then((result) => {
                             if(result){
                               location.reload();
                             }
                          })
                    } else {
                        Swal.fire('Deleted!', data.message, 'success');
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                      });
                }
            });
        }
    });
};

window.InitDataTableWithoutServerSide = async function (id) {
	$('#' + id).DataTable();
}

window.TextEditorTemplate = async function(id) {
    $('#' + id).summernote();
}

window.FormValidations = async function(id, rules, messages) {

    $('#' + id).validate({
        rules: rules,
        messages: messages,
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
}

// Function to display validation errors
function displayErrors(errors) {
    var errorHtml = '<ul>';
    errors.forEach(function(error) {
        errorHtml += '<li>' + error + '</li>';
    });
    errorHtml += '</ul>';
    $('#validationErrors').html(errorHtml);
}
