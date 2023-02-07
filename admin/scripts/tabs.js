let tabs = document.querySelectorAll(".tab");
let viewportScreen = document.querySelector(".viewport-screen");

tabs.forEach(t=> {
    t.addEventListener("click", e=> {
        t.classList.add("active-tab");
        removeActiveClass(t);
        viewportScreen.src = t.attributes["data-link"].value;
    });
});

function removeActiveClass(tab) {
    tabs.forEach(t=> {
        if (t != tab) {
            t.classList.remove("active-tab");
        }
    });
}
