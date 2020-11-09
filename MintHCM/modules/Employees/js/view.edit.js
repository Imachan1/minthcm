$('#photo_file').change(function(){
    $('#photo').val( $('#photo_file').val().replace("C:\\fakepath\\", ""));
});