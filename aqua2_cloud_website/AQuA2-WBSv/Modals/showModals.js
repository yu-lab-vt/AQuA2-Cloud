function showNoInstanceModal() {
    if(!$('#noInstance').hasClass('show'))
    {
        $("#noInstance").modal("show");
        $("#exitInstance").modal("hide");
        $("#discoverInstance").modal("hide");
        $("#busyInstance").modal("hide");
        $("#killInstance").modal("hide");
        $("#instanceException").modal("hide");
    }
}

function showExitInstanceModal() {
    if(!$('#exitInstance').hasClass('show'))
    {
        $("#exitInstance").modal("show");
        $("#noInstance").modal("hide");
        $("#discoverInstance").modal("hide");
        $("#busyInstance").modal("hide");
        $("#killInstance").modal("hide");
        $("#sysFinstanceExceptionailed").modal("hide");
    }
}

function showDiscoverInstanceModal() {
    if(!$('#discoverInstance').hasClass('show'))
    {
        $("#discoverInstance").modal("show");
        $("#noInstance").modal("hide");
        $("#exitInstance").modal("hide");
        $("#busyInstance").modal("hide");
        $("#killInstance").modal("hide");
        $("#instanceException").modal("hide");
    }
}

function showBusyInstanceModal() {
    if(!$('#busyInstance').hasClass('show'))
    {
        $("#busyInstance").modal("show");
        $("#noInstance").modal("hide");
        $("#exitInstance").modal("hide");
        $("#discoverInstance").modal("hide");
        $("#killInstance").modal("hide");
        $("#instanceException").modal("hide");
    }
}

function showKillInstanceModal() {
    if(!$('#killInstance').hasClass('show'))
    {
        $("#killInstance").modal("show");
        $("#noInstance").modal("hide");
        $("#exitInstance").modal("hide");
        $("#discoverInstance").modal("hide");
        $("#busyInstance").modal("hide");
        $("#instanceException").modal("hide");
    }
}

function showinstanceExceptionModal() {
    if(!$('#instanceException').hasClass('show'))
    {
        $("#instanceException").modal("show");
        $("#noInstance").modal("hide");
        $("#exitInstance").modal("hide");
        $("#discoverInstance").modal("hide");
        $("#busyInstance").modal("hide");
        $("#killInstance").modal("hide");
    }
}

function showinstanceExceptionModal() {
    if(!$('#instanceException').hasClass('show'))
    {
        $("#instanceException").modal("show");
        $("#noInstance").modal("hide");
        $("#exitInstance").modal("hide");
        $("#discoverInstance").modal("hide");
        $("#busyInstance").modal("hide");
        $("#killInstance").modal("hide");
    }
}

function hideAllModals() {
    $("#noInstance").modal("hide");
    $("#exitInstance").modal("hide");
    $("#discoverInstance").modal("hide");
    $("#busyInstance").modal("hide");
    $("#killInstance").modal("hide");
    $("#instanceException").modal("hide");
}

//console.log("loaded showModals.js");