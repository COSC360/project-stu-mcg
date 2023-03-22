window.onload = function(){
    const form = document.getElementById("login_form");
    
    form.addEventListener("submit", function(event) {
      event.preventDefault();
    
      const username = form.querySelector("input[type='text']").value;
      const password = form.querySelector("input[type='password']").value;
    
      if (username === "") {
        alert("Please enter a username.");
        return;
      }
      if (password === "") {
        alert("Please enter a password.");
        return;
      }
      //additional validation will be added later from database
    form.submit();
    });
}