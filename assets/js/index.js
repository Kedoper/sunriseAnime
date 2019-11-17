document.getElementById('loadMoreBtn').addEventListener('click', loadMoreBtnHandler);

function getAnimes(offset = null) {
    let xr = new XMLHttpRequest(),
        body = JSON.stringify({offset: offset});
    xr.open('POST', '/api/animes.get');
    xr.send(body);
    xr.onreadystatechange = function () {
        if (xr.readyState === 4 && xr.status === 200) {
            let response = JSON.parse(xr.response),
                items_wrap = document.querySelector('.anime-items__wrap');
            response.map(value => {
                let item_wrap = document.createElement('div');
                item_wrap.classList.add('anime-items__item');
                item_wrap.addEventListener('click',function () {
                   location.href = `/anime/${value.id}`;
                });

                let item_image = document.createElement('img');
                item_image.src = value.photo;

                item_wrap.append(item_image);

                let item_description = document.createElement('div');
                item_description.classList.add('recent-slider__item-description', 'item-description');

                let item_description_title = document.createElement('div'),
                    item_title = document.createElement('span');
                item_description_title.classList.add('item-description__item-title');
                item_title.innerText = value.title;
                item_description_title.append(item_title);

                let item_description_link = document.createElement('div'),
                    item_link = document.createElement('a');
                item_description_link.classList.add('item-description__item-link');
                item_link.href = `/anime/${value.id}`;
                item_link.innerText = "Смотреть";
                item_description_link.append(item_link);


                item_description.append(item_description_title, item_description_link);

                item_wrap.append(item_description);

                items_wrap.append(item_wrap);
            });
        }
    }
}

function loadMoreBtnHandler() {
    let offset = parseInt(this.dataset.offset);
    getAnimes(offset);
    offset += 24;
    this.dataset.offset = offset.toString();
}