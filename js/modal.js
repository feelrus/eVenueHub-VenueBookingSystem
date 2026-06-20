function openModal(venueID) {
    var modal = document.getElementById('myModal' + venueID);
    if (modal) {
        modal.style.display = "block";
    }
}

function closeModal(venueID) {
    var modal = document.getElementById('myModal' + venueID);
    if (modal) {
        modal.style.display = "none";
    }
}

window.onclick = function(event) {
    var modals = document.getElementsByClassName('modal');
    for (var i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
            modals[i].style.display = "none";
        }
    }
}


// var modal = document.getElementById("myModal");

// var btn = document.querySelector(".link-button");

// var span = document.getElementsByClassName("close")[0];

// function openModal() {
//     modal.style.display = "block";
// }

// function closeModal() {
//     modal.style.display = "none";
// }

// window.onclick = function(event) {
//     if (event.target == modal) {
//         closeModal();
//     }
// }