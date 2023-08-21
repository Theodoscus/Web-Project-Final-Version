function updateName() {
    
    
    // Get the updated value from the input field
    var updatedValue = document.getElementById('updated-name').value;
    console.log(updatedValue);
    
    // Update the input field's value
    document.getElementById('updated-name').value = updatedValue;
    
}

function updateDescription(){

    var updatedValue = document.getElementById('updated-description').value;
    document.getElementById('updated-description').value = updatedValue;
}