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
      const targetPosition = targetElement.getBoundingClientRect().top;

      if (typeof targetElement === 'undefined' || targetPosition <= 0) {
        return;
      }

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    });
  }
};
