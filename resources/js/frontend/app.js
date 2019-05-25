import smoothscroll from 'smoothscroll-polyfill';

if ('scrollBehavior' in document.documentElement.style !== true) {
  smoothscroll.polyfill();
}

const navBar = document.getElementsByClassName('navbar')[0];
const navLinks = document.querySelectorAll('#navbarSupportedContent a');

window.onload = () => {

  for (let j = 0 ; j < navLinks.length ; ++j) {
    const navLink = navLinks[j];
    const href = navLink.href.split('#')[1];

    if (typeof href === 'undefined') {
      continue;
    }

    navLink.addEventListener('click', (e)=>{
      e.preventDefault();

      const targetElement = document.getElementById(href);
      const navBarHeight = typeof navBar !== 'undefined' ? navBar.offsetHeight : 0;
      const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - navBarHeight;

      if (typeof targetElement === 'undefined') {
        return;
      }

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    });
  }
};
