window.onload = function(){
const form = document.getElementById("login_form");

form.addEventListener("submit", function(event) {
  event.preventDefault();

  const username = form.querySelector("input[type='text']").value;
  const email = form.querySelector("input[type='email']").value;
  const password = form.querySelector("input[type='password']").value;
  const confirmPassword = form.querySelectorAll("input[type='password']")[1].value;

  if (username === "") {
    alert("Please enter a username.");
    return;
  }

  if (!isValidEmail(email)) {
    alert("Please enter a valid email address.");
    return;
  }

  if (password.length < 8) {
    alert("Your password must be at least 8 characters long.");
    return;
  }

  if (password !== confirmPassword) {
    alert("Your passwords do not match.");
    return;
  }

  form.submit();
});

}
//email checker
function isValidEmail(email) {
  const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  return email.match(emailRegex);
}