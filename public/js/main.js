
// Проверка корректности URI

function checkUrl(url) {

    let URI = {
        main_scheme: '(https?:\/\/)?',
        domain: '([а-яa-z0-9-_\.\/]+)+',
        zone: '[\.][а-яa-z]{2,}',
        port: '([:][0-9]{1,4})?',
        path: '([а-яa-z0-9-_\.\/]+)?',
        query: '([a-z0-9\.\+-_=&%]+)?',
        file_type: '([;][a-z=]+)?',
        fragment: '([#][a-z0-9-_]+)?'
    }

    let regexp = '^';
    for(let i in URI) regexp += URI[i];
    regexp += '$';

    regexp = new RegExp(regexp, 'iu')
    return regexp.test(url);
}

// Получение короткой ссылки

async function getURL() {

    let form = document.forms[0];
    let errorText = document.querySelector('.ErrorText');
    let url = form.elements.url.value;
    url = url.trim();

    if(checkUrl(url)) {

        if(errorText) errorText.remove();

        let formData = new FormData();

        formData.append('url', url);

        let getLink = await fetch('/Ajax.php', {
            method: 'POST',
            body: formData
        });

        let data = await getLink.text();

        if(data.match(/\d/)) showMessage(errorText, data);
        else {

            let show = document.getElementById('show');
            show.classList.add('active');

            let original = document.getElementById('showOriginal');
            original.textContent = form.elements.url.value;
            form.elements.url.value = null;

            let showNew = document.getElementById('showNew');
            showNew.textContent = data;

            showNew.addEventListener('click', function() {

                var range = document.createRange();
                range.selectNode(showNew);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
            });
        }

    } else {
        showMessage(errorText, 2);
    }
}

// Сообщение с ошибкой

function showMessage(errorText, code) {

    let codes = {
        0: 'Ссылка не безопасна!',
        1: 'Сервис временно не доступен!',
        2: 'Используйте корректный URL!'
    };

    if(errorText) errorText.textContent = codes[code];
    else {

        let error = document.getElementById('error');
        let P = document.createElement("p");
        error.appendChild(P);

        P.className = 'ErrorText';
        P.textContent = codes[code];
    }
}