import AOS from "aos";

const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const mobileViewport = window.matchMedia("(max-width: 767px)");

const saveAosState = (element) => {
  if (!element.dataset.desktopAos) {
    element.dataset.desktopAos = element.dataset.aos || "none";
  }

  if (!element.dataset.desktopAosDelay) {
    element.dataset.desktopAosDelay = element.dataset.aosDelay || "0";
  }
};

const restoreDesktopAos = (element) => {
  if (!element.dataset.desktopAos) return;

  if (element.dataset.desktopAos === "none") {
    delete element.dataset.aos;
  } else {
    element.dataset.aos = element.dataset.desktopAos;
  }

  if (element.dataset.desktopAosDelay === "0") {
    delete element.dataset.aosDelay;
  } else {
    element.dataset.aosDelay = element.dataset.desktopAosDelay;
  }
};

const normalizeAosDelays = () => {
  document.querySelectorAll("[data-aos-delay]").forEach((element) => {
    saveAosState(element);

    const desktopDelay = Number(element.dataset.desktopAosDelay) || 0;

    element.dataset.aosDelay = mobileViewport.matches ? "0" : String(desktopDelay);
  });
};

const hidePageLoader = () => {
  const loader = document.querySelector("#page-loader");

  if (!loader) return;

  const removeLoader = () => {
    loader.remove();
    AOS.refreshHard();
  };

  if (prefersReducedMotion) {
    removeLoader();
    return;
  }

  loader.classList.add("is-hiding");
  window.requestAnimationFrame(removeLoader);
};

const initMobileMenu = () => {
  const button = document.querySelector("#menu-toggle");
  const menu = document.querySelector("#mobile-menu");
  const links = Array.from(document.querySelectorAll(".mobile-menu-link"));

  if (!button || !menu) return () => {};

  const setMenuState = (isOpen) => {
    button.setAttribute("aria-expanded", String(isOpen));
    menu.classList.toggle("hidden", !isOpen);
  };

  const toggleMenu = () => {
    const isOpen = button.getAttribute("aria-expanded") === "true";
    setMenuState(!isOpen);
  };

  const closeMenu = () => setMenuState(false);

  button.addEventListener("click", toggleMenu);
  links.forEach((link) => link.addEventListener("click", closeMenu));

  return () => {
    button.removeEventListener("click", toggleMenu);
    links.forEach((link) => link.removeEventListener("click", closeMenu));
  };
};

const initHeroParallax = () => {
  const hero = document.querySelector("#home");
  const heroBg = document.querySelector(".hero-bg");

  if (!hero || !heroBg || prefersReducedMotion) return () => {};

  let animationFrame = 0;

  const updateParallax = () => {
    animationFrame = 0;

    const rect = hero.getBoundingClientRect();
    const progress = Math.min(Math.max(-rect.top / rect.height, 0), 1);
    const translate = -8 + progress * 24;
    const scale = 1.06 + progress * 0.06;

    heroBg.style.transform = `translate3d(0, ${translate}%, 0) scale(${scale})`;
  };

  const requestUpdate = () => {
    if (!animationFrame) {
      animationFrame = window.requestAnimationFrame(updateParallax);
    }
  };

  updateParallax();
  window.addEventListener("scroll", requestUpdate, { passive: true });
  window.addEventListener("resize", requestUpdate);

  return () => {
    window.removeEventListener("scroll", requestUpdate);
    window.removeEventListener("resize", requestUpdate);

    if (animationFrame) {
      window.cancelAnimationFrame(animationFrame);
    }
  };
};

const refreshAosAfterHashJump = () => {
  window.setTimeout(() => AOS.refreshHard(), 180);
};

const refreshAosAfterImagesLoad = () => {
  document.querySelectorAll("img").forEach((image) => {
    if (image.complete) return;

    image.addEventListener("load", () => AOS.refreshHard(), { once: true });
    image.addEventListener("error", () => AOS.refreshHard(), { once: true });
  });
};

const initAos = () => {
  document.querySelectorAll("[data-desktop-aos]").forEach(restoreDesktopAos);
  normalizeAosDelays();

  AOS.init({
    duration: mobileViewport.matches ? 450 : 850,
    easing: "ease-out",
    offset: mobileViewport.matches ? 20 : 90,
    delay: 0,
    once: false,
    mirror: true,
    throttleDelay: 50,
    anchorPlacement: "top-bottom",
    disable: () => prefersReducedMotion,
  });
};

let cleanupMobileMenu = null;
let cleanupHeroParallax = null;

document.addEventListener("DOMContentLoaded", () => {
  cleanupMobileMenu = initMobileMenu();
  cleanupHeroParallax = initHeroParallax();
  initAos();
  refreshAosAfterImagesLoad();
  refreshAosAfterHashJump();
});

window.addEventListener(
  "load",
  () => {
    hidePageLoader();
    AOS.refreshHard();

    if (window.location.hash) {
      refreshAosAfterHashJump();
    }
  },
  { once: true },
);

window.setTimeout(hidePageLoader, 1800);
window.addEventListener("hashchange", refreshAosAfterHashJump);

mobileViewport.addEventListener("change", () => {
  document.querySelectorAll("[data-desktop-aos]").forEach(restoreDesktopAos);
  normalizeAosDelays();
  AOS.refreshHard();
});

window.addEventListener("pagehide", () => {
  cleanupMobileMenu?.();
  cleanupMobileMenu = null;
  cleanupHeroParallax?.();
  cleanupHeroParallax = null;
});

if (import.meta.hot) {
  import.meta.hot.dispose(() => {
    cleanupMobileMenu?.();
    cleanupMobileMenu = null;
    cleanupHeroParallax?.();
    cleanupHeroParallax = null;
  });
}
