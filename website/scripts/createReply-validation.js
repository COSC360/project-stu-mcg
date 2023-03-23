window.onload = function(){
    const form = document.getElementById("createReplyForm");
    
    form.addEventListener("submit", function(event) {
      event.preventDefault();
    
      const text = form.querySelector("textarea").value;
    
      if (text === "") {
        alert("Reply cannot be blank.");
        return;
      }
    form.submit();
    });
}