
$("#searchByLogin").on("keyup", function(e) {
    filter = $("#searchByLogin").val().toUpperCase();
    $("#searchByemail").val("")
    row = $(".table-row");
    login = $(".login");
    for (i = 0; i < row.length; i++) {
        bylogin = login[i].textContent || login[i].innerText;
        if (bylogin.toUpperCase().indexOf(filter) > -1) {
            row[i].setAttribute('style', 'display: table-row;');
        } else {
            row[i].setAttribute('style', 'display: none;');
        }
    }
})

$("#searchByemail").on("keyup", function(e) {
    filter = $("#searchByemail").val().toUpperCase();
    $("#searchByLogin").val("");
    row = $(".table-row");
    email = $(".email");
    for (i = 0; i < row.length; i++) {
        byEmail = email[i].textContent || email[i].innerText;
        if (byEmail.toUpperCase().indexOf(filter) > -1) {
            row[i].setAttribute('style', 'display: table-row;');
        } else {
            row[i].setAttribute('style', 'display: none;');
        }
    }
})
