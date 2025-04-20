window.addEventListener("load", function () {
    const preloader = document.getElementById("preloader");
    setTimeout(() => {
        preloader.style.opacity = "0";
        setTimeout(() => {
            preloader.style.display = "none";
        }, 800); // същото като CSS transition
    }, 2600); // изчаква края на лоудинг анимацията
});