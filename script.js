
function showPopup() {
  document.getElementById('popup').style.display = 'block';
}

function closePopup() {
  document.getElementById('popup').style.display = 'none';
}

function decreaseQuantity(button) {
  var input = button.nextElementSibling;
  if (input.value > 1) {
    input.value--;
  }
}

function increaseQuantity(button) {
  var input = button.previousElementSibling;
  input.value++;
}

function validateQuantity(input) {
  if (input.value < 1) {
    input.value = 1;
  }
}
   