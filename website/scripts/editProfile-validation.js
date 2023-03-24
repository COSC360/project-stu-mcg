window.onload = function(){
const form = document.getElementById("editProfile_form");
if(form != null){
  form.addEventListener("submit", function(event) {
    event.preventDefault();

    const profileImage = form.querySelectorAll("input[type='file']")[0].value;

    if(profileImage !== "" && !isValidImageFile(profileImage)){
      alert("Must upload a vaild image file.");
      return;
    }

    form.submit();
  });
}

}

function isValidImageFile(file){
  const imageFileRegex = /[^\s]+(.*?).(jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF)$/; 
  return file.match(imageFileRegex);
}