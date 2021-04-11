// forEach method
var forEach = function (array, callback, scope) {
    for (var i = 0; i < array.length; i++) {
        callback.call(scope, i, array[i]);
    }
};

var spinner = document.querySelector("#spinner"),
    angle = 0,
    images = document.querySelectorAll("#spinner figure"),
    numpics = images.length,
    degInt = 360 / numpics,
    start = 0,
    current = 1;

forEach(images, function (index, value) {
    images[index].style.webkitTransform = "rotateY(-" + start + "deg)";
    images[index].style.transform = "rotateY(-" + start + "deg)";
    images[index].addEventListener("click", function () {
        if (this.classList.contains('current')) {
            this.classList.toggle("focus");
        }
    })
    start = start + degInt;
});

function setCurrent(current) {
    document.querySelector('figure#spinner figure:nth-child(' + current + ')').classList.add('current');
}

function galleryspin(sign) {
    forEach(images, function (index, value) {
        images[index].classList.remove('current');
        images[index].classList.remove('focus');
        images[index].classList.remove('caption');
    })

    if (!sign) {
        angle = angle + degInt;
        current = (current + 1);
        if (current > numpics) {
            current = 1;
        }
    } else {
        angle = angle - degInt;
        current = current - 1;
        if (current == 0) {
            current = numpics;
        }
    }

    spinner.setAttribute("style", "-webkit-transform: rotateY(" + angle + "deg); transform: rotateY(" + angle + "deg)");
    setCurrent(current);
}

document.body.onkeydown = function (e) {
    switch (e.which) {
        case 37: // left cursor
            galleryspin('-');
            break;

        case 39: // right cursor
            galleryspin('');
            break;

        case 90: // Z - zoom image in forefront image
            document.querySelector('figure#spinner figure.current').classList.toggle('focus');
            break;

        case 67: // C - show caption for forefront image
            document.querySelector('figure#spinner figure.current').classList.toggle('caption');
            break;

        default:
            return; // exit this handler for other keys
    }
    e.preventDefault();
}

function mouseMove() {

}

function mouseUp() {
    document.removeEventListener("mousemove", mouseMove, false);
    document.removeEventListener("mouseup", mouseUp, false);
}

spinner.addEventListener("mousedown", function (e) {
    var startX = e.pageX;

    document.addEventListener("mousemove", mouseMove, false);
    document.addEventListener("mouseup", mouseUp, false);
}, false);

setCurrent(1);

