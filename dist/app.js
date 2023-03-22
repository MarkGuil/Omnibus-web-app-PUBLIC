const sections = document.querySelectorAll(".sect");
const links = document.querySelectorAll(".section-links");
const navbar = document.querySelector(".navbar");
const footerObserver = document.querySelector(".footer-observer");
const footer = document.querySelector(".page-footer");
const mobileNav = document.getElementById("nav-menu");
const hamburger = document.getElementById("hamburger");

window.onscroll = function(){
    if(window.pageYOffset === 0){
        navbar.classList.add("bg-transparent");
        navbar.classList.add("py-5");
        mobileNav.classList.add("top-20");
        hamburger.classList.add("top-10");
        navbar.classList.remove("bg-dark-bluer/[.9]");
        navbar.classList.remove("py-3");
        mobileNav.classList.remove("top-[3.75rem]");
        hamburger.classList.remove("top-8");
    } else {
        navbar.classList.add("bg-dark-bluer/[.9]");
        navbar.classList.add("py-3");
        mobileNav.classList.add("top-[3.75rem]");
        hamburger.classList.add("top-8");
        navbar.classList.remove("bg-transparent");
        navbar.classList.remove("py-5");
        mobileNav.classList.remove("top-20");
        hamburger.classList.remove("top-10");
    }
}

const observer = new IntersectionObserver(
    entries => {
        entries.forEach(entry => {
            // entry.target.classList.toggle("text-center", entry.isIntersecting);
            if(entry.isIntersecting) {
                activatelink(entry.target.classList[1]);
                // observer.unobserve(entry.target)
            }
        })
    }, {
        threshold: .3,
    }
)

const observer2 = new IntersectionObserver(
    entries => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                footer.classList.add("fixed");
            } else {
                // console.log(entry.isIntersecting);
                footer.classList.remove("fixed");
            }
        })
    }
)

function activatelink(linkPos) {
    links.forEach(linkName => {
        if(linkName.classList.contains(linkPos)) {
            linkName.classList.add("text-white");
            linkName.classList.add("before:visible");
            linkName.classList.add("before:w-6");
            linkName.classList.remove("text-slate-300");
            linkName.classList.remove("before:invisible");
            linkName.classList.remove("before:w-0")
        }
        else {
            linkName.classList.add("text-slate-300");
            linkName.classList.add("before:invisible");
            linkName.classList.add("before:w-0");
            linkName.classList.remove("text-white");
            linkName.classList.remove("before:visible");
            linkName.classList.remove("before:w-6")
        }
    })
}

sections.forEach(card => {
    observer.observe(card);
});

observer2.observe(footerObserver);
