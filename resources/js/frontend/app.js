import smoothscroll from 'smoothscroll-polyfill';

if ('scrollBehavior' in document.documentElement.style !== true) {
  smoothscroll.polyfill();
}

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

      if (typeof targetElement === 'undefined') {
        return;
      }

      window.scrollTo({
        top: targetElement.getBoundingClientRect().top,
        behavior: 'smooth'
      });
    });
  }
};
