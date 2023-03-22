window.onload = function(){
    const form = document.getElementById("createThreadForm");
    
    form.addEventListener("submit", function(event) {
      event.preventDefault();
    
      const title = form.querySelector("input[type='text']").value;
      const text = form.querySelector("textarea").value;
    
      if (title === "") {
        alert("Please enter a title.");
        return;
      }
      if (text === "") {
        alert("Threads cannot have an empty message.");
        return;
      }
      //additional validation will be added later from database
    form.submit();
    });
}