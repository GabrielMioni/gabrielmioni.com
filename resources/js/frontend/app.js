import smoothscroll from 'smoothscroll-polyfill';

if ('scrollBehavior' in document.documentElement.style !== true) {
  smoothscroll.polyfill();
}

const navClick = (e, navBar) => {
  e.preventDefault();
  const href = e.target.href.split('#')[1];
  if (typeof href === 'undefined') {
    return;
  }

  const targetElement = document.getElementById(href);
  const navBarHeight = typeof navBar !== 'undefined' ? navBar.offsetHeight : 0;
  const targetPosition = targetElement === null ? 0 : targetElement.getBoundingClientRect().top + window.scrollY - navBarHeight;

  if (typeof targetElement === 'undefined') {
    return;
  }

  window.scrollTo({
    top: targetPosition,
    behavior: 'smooth'
  });
};

const addNavClickEvent = (linkElements, navBar) => {
  for (let j = 0 ; j < linkElements.length ; ++j) {
    const link = linkElements[j];

    link.addEventListener('click', (e)=>{
      navClick(e, navBar);
    });
  }
};

window.onload = () => {
  const navBar = document.getElementsByClassName('navbar')[0];
  const navLinks = document.querySelectorAll('#navbarSupportedContent a');
  const heroLinks = document.querySelectorAll('.hero a');

  addNavClickEvent(navLinks, navBar);
  addNavClickEvent(heroLinks, navBar);

  const homeLink = document.getElementsByClassName('home-link')[0];

  document.addEventListener('scroll', () => {
    const displaySet = homeLink.classList.contains('display');
    const scrollPos  = window.scrollY;
    if (!displaySet && scrollPos > 300) {
      homeLink.classList.add('display');
    }
    if (displaySet && scrollPos <= 300) {
      homeLink.classList.remove('display');
    }
  });
};
