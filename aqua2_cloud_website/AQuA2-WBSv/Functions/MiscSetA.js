//Instance info indicator
function SetInstanceInfoIndicator(text, bgcolor, color)
{
    document.getElementById("instanceInfoText").innerHTML = text;
    document.getElementById("instanceInfoText").style.backgroundColor = bgcolor;
    document.getElementById("instanceInfoText").style.color = color;
    document.getElementById("instanceInfoText").style.fontWeight = "bold";
}

//Timer for checking if the user is inactive. Run check every 5 seconds
setInterval(function() 
{
    $.ajax({
        type: 'GET',
        async: false,
        url: '../assets/includes/checkinactive.ajax.php',
        success: function(response) {
            if (response == 'logout_redirect') {
                location.href = "../home/";
            }
        }
    });
    }, 5000);

//File manager functions
function get_checkboxes() { for (var e = document.getElementsByName("file[]"), t = [], n = e.length - 1; n >= 0; n--) (e[n].type = "checkbox") && t.push(e[n]); return t }
function change_checkboxes(e, t) { for (var n = e.length - 1; n >= 0; n--) {e[n].checked = "boolean" == typeof t ? t : !e[n].checked }}
function unselect_all() { change_checkboxes(get_checkboxes(), !1); referencedFileFull = ""; toast("Selection cleared...");}
function checkbox_toggle() { var e = get_checkboxes(); e.push(this);change_checkboxes(e);}
$('.custom-control-input').click(function() 
{
    var e = get_checkboxes();
    unselect_all();
    this.checked = true;
    referencedFileName = this.value;
    referencedFileDirectory = document.getElementById("currentDirectory_HTML_JS_Handle").textContent;
    referencedFileDirectory = referencedFileDirectory + "/";
    referencedFileFull = referencedFileDirectory + referencedFileName;
    toast("File selected: " + referencedFileFull);		
});

//Toast
function toast(txt) 
{ 
    document.getElementById("snackbar").innerHTML = txt;
    document.getElementById("snackbar").className = "show";
    toastElapsed = 3000; //Show toast for 3 seconds
}