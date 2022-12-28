function passclick(fileid){
    document.getElementById(fileid).click();
}
function loadimage(e,imageid)
{
    if(e.files[0]){
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(imageid).setAttribute('src',e.target.result) ;
        }
        reader.readAsDataURL(e.files[0]) ;
    }
}