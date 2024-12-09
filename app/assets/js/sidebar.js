
document.addEventListener("DOMContentLoaded", function (event) {

    cont = 0;
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId),
                bodypd = document.getElementById(bodyId),
                headerpd = document.getElementById(headerId)
        let navLogo = document.getElementById('nav_logo')
        let contentNav = document.getElementById('conteudo-nav')

// Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
// show navbar
                nav.classList.toggle('show-nav')
// change icon
                toggle.classList.toggle('bx-x')
// add padding to body
                bodypd.classList.toggle('body-pd')
// add padding to header
                headerpd.classList.toggle('body-pd')

                contentNav.classList.toggle('width-nav');

                    
                    navLogo.classList.toggle('mostra-logo');
             
            });
        }
    }

    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'))
            this.classList.add('active')
        }
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink))


    for (var i = 0; i < document.links.length; i++) {
        if (document.links[i].href == document.URL) {
            document.links[i].className = 'nav_link active';
        }
    }
});
