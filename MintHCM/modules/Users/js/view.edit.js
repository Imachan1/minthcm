
function getSupervisedUnitsIfEmployeeIsSupervisor() {
    let status = $('select[name="status"] option:selected').val();
    let employee_status = $('select[name="employee_status"] option:selected').val();
    if (employee_status != 'Active' || status != 'Active') {
        let employee_id = $('#record').val();
        viewTools.api.callCustomApi({
            module: 'Employees',
            action: 'checkIfEmployeeIsSupervisor',
            dataPOST: {employee_id: employee_id, employee_status: employee_status},
            async: false,
            callback: function (response) {
                if (response != false) {
                    displayConfirmationWindow(response);
                }
                else {
                    SUGAR.ajaxUI.submitForm("EditView");
                    return false;
                }
            }
        });
    } else {
        SUGAR.ajaxUI.submitForm("EditView");
        return false;
    }
}

function displayConfirmationWindow(response) {
    let units_links = '';
    let dialog_div = '';
    for (const [key, value] of Object.entries(response)) {
        units_links += '<a href="' + location["href"] + '?module=SecurityGroups&action=DetailView&record=' + key + '" target="_blank">' + value + '</a></br>';
    }
    if ($('html').is(':lang(pl_PL)')) {
        dialog_div = $('<div>').css('display', 'none').html('Pracownik, którego chcesz dezaktywować jest aktualnym kierownikiem jednostki</br>' + units_links + 'Aby poprawnie wygenerować strukturę organizacyjną działu, należy wskazać nowego kierownika. Czy chcesz kontynuować dezaktywację?')
    }
    else {
        dialog_div = $('<div>').css('display', 'none').html('The employee you want to deactivate is the current manager of the unit</br>' + units_links + 'In order to correctly generate the organizational structure of the department, a new manager should be indicated. Do you want to continue with deactivation?')
    }

    dialog_div.dialog({
        resizable: false,
        height: 250,
        width: 400,
        modal: true,
        buttons: [
            {
                text: viewTools.language.get('Users', 'LBL_USERS_CONFIRMATION_BUTTON_CONFIRM'),
                click: function () {
                    $(this).dialog("close");
                    runUsersForceSave();
                },
            },
            {
                text: viewTools.language.get('Users', 'LBL_USERS_CONFIRMATION_BUTTON_CANCEL'),
                click: function () {
                    $(this).dialog("close");
                },
            }
        ]
  });
}

function runUsersForceSave() {
    SUGAR.ajaxUI.submitForm("EditView");
    return false;
}
