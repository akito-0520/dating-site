function checkLimit() { // 選択されているチェックボックスの数を数える関数
    var checkboxes = document.getElementsByName("hobby_id[]");
    var checkedCount = 0;

    // 選択されているチェックボックスの数を数える
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            checkedCount++;
        }
    }

    // 制限数を超えた場合は選択を無効化する
    if (checkedCount > 4) {
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].disabled = !checkboxes[i].checked;
        }
    } else {
        // 制限数以下の場合はすべてのチェックボックスを有効にする
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].disabled = false;
        }
    }
}
