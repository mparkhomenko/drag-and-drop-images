$(document).ready(function () {
    $('#send').on('click', function() {
        let regExpEmail = /[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i,
            name = $('#name').val(),
            email = $('#email').val(),
            message = $('#message').val(),
            phone = $('#phone').val();

        if(regExpEmail.exec(email) && name != '' && phone != '') {
            $('#send').attr("disabled", "disabled");
            let formData = new FormData($('form')[0]),
                i;
            formData.append("name", name);
            formData.append("email", email);
            formData.append("message", message);
            formData.append("phone", phone);
            for(i = 0; i < tmp.length; i++) {
                    formData.append('file[]', tmp[i]);
            }

            console.log(tmp);

            $.ajax({
                type: "POST",
                url: "script.php",
                processData: false,
                contentType: false,
                cache: false,
                data: formData
            }).done(function (data) {
                if (data == "ok") {
                    $('#name').val(''),
                    $('#email').val(''),
                    $('#message').val(''),
                    $('#phone').val('');
                    $("#send").removeAttr("disabled");
                } else {
                    alert("Что-то пошло не так. Проверьте правильность введённых данных");
                }
            });
        } else {
            alert("Что-то пошло не так. Возможно вы не корректно заполнили поля");
        }
    });
});