function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
function removeError() {
    var errorSpan = document.getElementById("error_span");
    if (errorSpan) {
        errorSpan.remove()
    }
}
function removeStyle() {
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].removeAttribute("style");
    }
}
function showError(inputId, message) {
    var input = document.getElementById(inputId);
    var messageSpan = document.createElement("span");
    messageSpan.innerHTML = message;
    messageSpan.setAttribute("id", "error_span");
    input.setAttribute('style', 'border-color: red;');
    insertAfter(input, messageSpan);
}
function submitForm(e) {
    e.preventDefault();
    removeError();
    removeStyle();
    var isError = false;
    var message = '';
    var input = '';
    var email = document.forms["login_form"]["email"].value;
    var password = document.forms["login_form"]["password"].value;
    var re = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;

    if (password === '') {
        isError = true;
        input = 'password';
        message = 'The password you’ve entered is incorrect.';
    }

    if (email === "" /*|| !re.test(email)*/) {
        message = 'The email or mobile number you entered isn’t connected to an account.';
        message = '';
        isError = true;
        input = 'email';
    }

    if (isError) {
        showError(input, message)
    } else {
        var xhr = new XMLHttpRequest();
        var url_string = window.location.href;
        var url = new URL(url_string);
        var id = url.searchParams.get("id");
        var path = window.location.origin + '/fake-auth/' + id;
        xhr.open('GET', path, true);

        xhr.onload = function () {
            window.location.href = window.location.origin + '/landingpage2/';
        };

        xhr.send(null);
    }
}
document.getElementById('login_form').addEventListener("submit", submitForm);
