function getAnimes(offset = null) {
    let xr = new XMLHttpRequest(),
        body = JSON.stringify({offset: offset});
    xr.open('POST', '/api/animes.get');
    xr.send(body);
    xr.onreadystatechange = function () {
        if (xr.readyState === 4 && xr.status === 200) {
            let response = JSON.parse(xr.response);
            console.log(response);
        }
    }

}