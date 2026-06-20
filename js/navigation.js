document.getElementById('venue-nav-toggle').addEventListener('click', function() {
    document.getElementById('side-nav').style.width = '250px';
});

document.getElementById('side-nav-close').addEventListener('click', function() {
    document.getElementById('side-nav').style.width = '0';
});