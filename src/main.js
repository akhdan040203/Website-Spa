import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import "./styles.css";

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

const hidePageLoader = () => {
  const loader = document.querySelector("#page-loader");

  if (!loader) return;

  const removeLoader = () => loader.remove();

  if (prefersReducedMotion) {
    removeLoader();
    return;
  }

  gsap.to(loader, {
    opacity: 0,
    duration: 0.75,
    ease: "power2.out",
    onComplete: removeLoader,
  });
};

const createScrollReveal = (target, vars = {}) => {
  const elements = gsap.utils.toArray(target);

  elements.forEach((element) => {
    gsap.from(element, {
      scrollTrigger: {
        trigger: element,
        start: "top 84%",
        once: true,
      },
      y: vars.y ?? 42,
      rotate: vars.rotate ?? 0,
      opacity: 0,
      duration: vars.duration ?? 0.85,
      ease: "power3.out",
    });
  });
};

const initPackageTilt = (cards) => {
  const cleanupHandlers = [];

  cards.forEach((card) => {
    const rotateX = gsap.quickTo(card, "rotateX", { duration: 0.28, ease: "power2.out" });
    const rotateY = gsap.quickTo(card, "rotateY", { duration: 0.28, ease: "power2.out" });

    let bounds = null;
    let pointer = null;
    let animationFrame = 0;

    const updateTilt = () => {
      animationFrame = 0;

      if (!bounds || !pointer) return;

      const x = pointer.clientX - bounds.left - bounds.width / 2;
      const y = pointer.clientY - bounds.top - bounds.height / 2;

      rotateY(x / 28);
      rotateX(-y / 28);
    };

    const requestTiltUpdate = (event) => {
      pointer = event;

      if (!animationFrame) {
        animationFrame = window.requestAnimationFrame(updateTilt);
      }
    };

    const handlePointerEnter = () => {
      bounds = card.getBoundingClientRect();
      gsap.set(card, { transformPerspective: 900 });
    };

    const handlePointerLeave = () => {
      pointer = null;
      bounds = null;

      if (animationFrame) {
        window.cancelAnimationFrame(animationFrame);
        animationFrame = 0;
      }

      rotateX(0);
      rotateY(0);
    };

    card.addEventListener("pointerenter", handlePointerEnter);
    card.addEventListener("pointermove", requestTiltUpdate);
    card.addEventListener("pointerleave", handlePointerLeave);

    cleanupHandlers.push(() => {
      card.removeEventListener("pointerenter", handlePointerEnter);
      card.removeEventListener("pointermove", requestTiltUpdate);
      card.removeEventListener("pointerleave", handlePointerLeave);

      if (animationFrame) {
        window.cancelAnimationFrame(animationFrame);
      }
    });
  });

  return () => cleanupHandlers.forEach((cleanup) => cleanup());
};

const initAnimations = () => {
  const cleanupHandlers = [];

  if (prefersReducedMotion) {
    return () => {};
  }

  const context = gsap.context(() => {
    gsap.fromTo(
      ".loader-line",
      { xPercent: -120 },
      {
        xPercent: 220,
        duration: 1.15,
        repeat: -1,
        ease: "power2.inOut",
      },
    );

    gsap.from(".hero-copy > *", {
      y: 34,
      opacity: 0,
      duration: 1,
      stagger: 0.12,
      ease: "power3.out",
    });

    gsap.from(".hero-art > *", {
      y: 42,
      opacity: 0,
      duration: 1.1,
      stagger: 0.16,
      delay: 0.25,
      ease: "power3.out",
    });

    gsap.to(".marquee-track", {
      xPercent: -35,
      duration: 18,
      repeat: -1,
      ease: "none",
    });

    createScrollReveal(".reveal, .testimonial-card, .benefit-item");
    createScrollReveal(".package-card", { y: 34, duration: 0.75 });

    gsap.utils.toArray(".dark-photo").forEach((photo, index) => {
      createScrollReveal(photo, {
        y: 54,
        rotate: index % 2 === 0 ? -7 : 7,
        duration: 1,
      });
    });

    cleanupHandlers.push(initPackageTilt(gsap.utils.toArray(".package-card")));
  }, document.body);

  return () => {
    cleanupHandlers.forEach((cleanup) => cleanup());
    context.revert();
  };
};

let cleanupAnimations = null;

document.addEventListener("DOMContentLoaded", () => {
  cleanupAnimations = initAnimations();
});

window.addEventListener("load", hidePageLoader, { once: true });
window.setTimeout(hidePageLoader, 1800);

window.addEventListener("pagehide", () => {
  cleanupAnimations?.();
  cleanupAnimations = null;
});

if (import.meta.hot) {
  import.meta.hot.dispose(() => {
    cleanupAnimations?.();
    cleanupAnimations = null;
  });
}
