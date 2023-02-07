let menuItems = document.querySelectorAll(".ddl");

menuItems.forEach(menuitem => {
    menuitem.addEventListener("click", e => {
        if (e.target == menuitem.children[0]) {
            let tempvar = menuitem.children[1];
            tempvar.classList.toggle("hidden");
        }
    });
});


let mainmenuItems = document.querySelectorAll(".menu-item");

mainmenuItems.forEach(mainmenuitem => {
    mainmenuitem.addEventListener("click", e => {
        mainmenuitem.classList.add("mainactive");
        removeActive(mainmenuitem, mainmenuItems, "mainactive");
    });
});






let submenuItems = document.querySelectorAll(".submenu-list-item");

submenuItems.forEach(menuitem => {
    menuitem.addEventListener("click", e => {
        menuitem.classList.add("active");
        removeActive(menuitem, submenuItems, "active");
    });
});



function removeActive(menuitem, arr, className) {
    arr.forEach(mi => {
        if (mi != menuitem) {
            mi.classList.remove(className);
        }
    });
}






// sidebar logic
var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
let sidebarIsOpen = true;

let hamMenu = document.querySelector(".ham-menu");
let sidebar = document.querySelector(".sidebar");
let mainView = document.querySelector(".main-view");

hamMenu.addEventListener("click", e => {
    mainView.classList.toggle("opened-main-view");
    if (sidebarIsOpen) {
        closeMenu();
        sidebarIsOpen = false;
    } else {
        OpenMenu();
        sidebarIsOpen = true;
    }
});


function closeMenu() {
    if (width < 600) {
        sidebar.style.left = "-70%";
    } else {
        mainView.style.width = "100%";
        mainView.style.left = "0";
        sidebar.style.left = "-15%";
    }
    mainView.classList.toggle("opened-main-view");

}

function OpenMenu() {
    if (width > 600) {
        mainView.style.left = "15%";
        mainView.style.width = "85%";
    }
    sidebar.style.left = "0%";
}

//linking logic
let links = document.querySelectorAll(".f-link");
let targetContainer = document.querySelector(".target-container");

links.forEach(l => {
    l.addEventListener("click", e => {
        if (width < 600) {
            closeMenu();
        }
        targetContainer.src = l.attributes["data-link"].value;
    });
});