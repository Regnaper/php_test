function setFirstTest(value) {
    if (value) document.getElementById('testTest').href = "/test.php?first=on";
    else document.getElementById('testTest').href = "/test.php";
}


function setAll(value, itemName) {
    if (value) {
        itemsArray.forEach(function(item){
            $(`#${itemName}_${item}`).prop("checked", true);
        });
    } else {
        itemsArray.forEach(function (item) {
            $(`#${itemName}_${item}`).prop("checked", false);
        });
    }
}


function setAllOff(value, itemName) {
    if (value) {
        allChecked = Boolean(true);
        itemsArray.forEach(function(item){
            itemCheckbox = document.getElementById(`${itemName}_${item}`);
            if (!itemCheckbox.checked) {
                allChecked = false;
                return false;
            }
        });
        if (allChecked) $(`#all`).prop("checked", true);
    } else $(`#all`).prop("checked", false);
}

function deletePost() {
    $("#delete").prop("value", true);
}

function newItem() {
    $("#new").prop("value", true);
}