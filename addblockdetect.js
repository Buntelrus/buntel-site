function handleaddblock() {
    console.log('blocked')
    fetch('addblock.html')
        .then(response => response.text())
        .then(function (responseText) {
            document.body.innerHTML = responseText;
        })
}