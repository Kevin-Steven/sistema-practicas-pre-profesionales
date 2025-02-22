// SCROLL SECTIONS
let menuIcon = document.querySelector('#menu-icon');
let navbar = document.querySelector('.navbar');

menuIcon.onclick = () => {
    menuIcon.classList.toggle('bx-x');
    navbar.classList.toggle('active');
}


const sections = document.querySelectorAll('section');
const navLinks = document.querySelectorAll('header nav a');

function handleScroll() {
    const scrollPosition = window.scrollY;

    sections.forEach(section => {
        const sectionOffset = section.offsetTop - 100;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');

        if (scrollPosition >= sectionOffset && scrollPosition < sectionOffset + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('active');
            });

            const activeLink = document.querySelector(`header nav a[href*='${sectionId}']`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    });

    menuIcon.classList.remove('bx-x');
    navbar.classList.remove('active');
}

window.addEventListener('scroll', handleScroll);
