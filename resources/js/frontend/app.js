window.onload = () => {
  const navlinks = document.querySelectorAll('#navbarSupportedContent a');

  let scrollLinks = {};

  for (let j = 0 ; j < navlinks.length ; ++j) {
    const navlink = navlinks[j];
    const href = navlink.href.split('#')[1];

    if (typeof href === 'undefined') {
      continue;
    }

    const targetElement = document.getElementById(href);
    scrollLinks[href] = targetElement.getBoundingClientRect().top;

    navlink.addEventListener('click', (e)=>{
      e.preventDefault();

      window.scrollTo({
        top: scrollLinks[href],
        behavior: 'smooth'
      });
    });
  }
};
