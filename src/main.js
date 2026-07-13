import AOS from "aos";

const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

AOS.init({
  duration: reducedMotion ? 0 : 800,
  easing: "ease-out-cubic",
  once: true,
  offset: 70,
  disable: reducedMotion,
});

const header = document.querySelector("[data-site-header]");
const menuToggle = document.querySelector("[data-menu-toggle]");
const menu = document.querySelector("[data-menu]");

const syncHeader = () => header?.classList.toggle("is-scrolled", window.scrollY > 24);
syncHeader();
window.addEventListener("scroll", syncHeader, { passive: true });

menuToggle?.addEventListener("click", () => {
  const open = menuToggle.getAttribute("aria-expanded") === "true";
  menuToggle.setAttribute("aria-expanded", String(!open));
  menu?.classList.toggle("hidden", open);
});

menu?.querySelectorAll("a").forEach((link) => link.addEventListener("click", () => {
  menuToggle?.setAttribute("aria-expanded", "false");
  menu.classList.add("hidden");
}));

const trackLead = (label = "WhatsApp CTA") => {
  window.fbq?.("track", "Lead", { content_name: label });
  window.gtag?.("event", "conversion", { send_to: "AW-18197334083" });
};

const animateCounter = (counter) => {
  const target = Number(counter.dataset.target || 0);
  const suffix = counter.dataset.suffix || "";
  if (reducedMotion) { counter.textContent = `${target}${suffix}`; return; }
  const start = performance.now();
  const tick = (now) => {
    const progress = Math.min((now - start) / 1200, 1);
    counter.textContent = `${Math.round(target * (1 - Math.pow(1 - progress, 3)))}${suffix}`;
    if (progress < 1) requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
};

const counters = document.querySelectorAll("[data-count-up]");
if ("IntersectionObserver" in window) {
  const observer = new IntersectionObserver((entries, currentObserver) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      animateCounter(entry.target);
      currentObserver.unobserve(entry.target);
    });
  }, { threshold: 0.35 });
  counters.forEach((counter) => observer.observe(counter));
} else counters.forEach(animateCounter);

const serviceCarousel = document.querySelector("[data-service-carousel]");
if (serviceCarousel) {
  const track = serviceCarousel.querySelector("[data-service-track]");
  const cards = [...serviceCarousel.querySelectorAll("[data-service-card]")];
  const previous = serviceCarousel.querySelector("[data-service-prev]");
  const next = serviceCarousel.querySelector("[data-service-next]");
  let page = 0;
  let timer = 0;
  const gap = () => Number.parseFloat(getComputedStyle(track).columnGap) || 0;
  const update = (nextPage) => {
    if (!track || !cards.length) return;
    page = (nextPage + cards.length) % cards.length;
    const width = cards[0].getBoundingClientRect().width;
    const viewport = track.parentElement.clientWidth;
    const desired = page * (width + gap()) - (viewport - width) / 2;
    const offset = Math.min(Math.max(desired, 0), Math.max(track.scrollWidth - viewport, 0));
    track.style.transform = `translateX(-${offset}px)`;
    cards.forEach((card, index) => card.classList.toggle("is-active", index === page));
  };
  const restart = () => {
    clearInterval(timer);
    if (!reducedMotion) timer = window.setInterval(() => update(page + 1), 3400);
  };
  previous?.addEventListener("click", () => { update(page - 1); restart(); });
  next?.addEventListener("click", () => { update(page + 1); restart(); });
  cards.forEach((card, index) => card.addEventListener("click", (event) => {
    if (event.target.closest("a")) return;
    update(index); restart();
  }));
  update(0); restart();
  window.addEventListener("resize", () => update(page));
}

const testimonialCarousel = document.querySelector("[data-testimonial-carousel]");
if (testimonialCarousel) {
  const track = testimonialCarousel.querySelector("[data-testimonial-track]");
  const cards = [...testimonialCarousel.querySelectorAll("[data-testimonial-card]")];
  const pagination = testimonialCarousel.querySelector("[data-testimonial-pagination]");
  let page = 0;
  let timer = 0;
  const gap = () => Number.parseFloat(getComputedStyle(track).columnGap) || 0;
  const dots = cards.map((_, index) => {
    const dot = document.createElement("button");
    dot.type = "button"; dot.className = "testimonial-dot";
    dot.setAttribute("aria-label", `Tampilkan testimoni ${index + 1}`);
    dot.addEventListener("click", () => { update(index); restart(); });
    pagination?.append(dot); return dot;
  });
  const update = (nextPage) => {
    if (!track || !cards.length) return;
    page = (nextPage + cards.length) % cards.length;
    const width = cards[0].getBoundingClientRect().width;
    const viewport = track.parentElement.clientWidth;
    const desired = page * (width + gap()) - (viewport - width) / 2;
    const offset = Math.min(Math.max(desired, 0), Math.max(track.scrollWidth - viewport, 0));
    track.style.transform = `translateX(-${offset}px)`;
    cards.forEach((card, index) => card.classList.toggle("is-active", index === page));
    dots.forEach((dot, index) => {
      dot.classList.toggle("is-active", index === page);
      dot.setAttribute("aria-current", index === page ? "true" : "false");
    });
  };
  const restart = () => {
    clearInterval(timer);
    if (!reducedMotion) timer = window.setInterval(() => update(page + 1), 3800);
  };
  update(0); restart();
  window.addEventListener("resize", () => update(page));
}

const modal = document.querySelector("#wa-picker");
const panel = modal?.querySelector(".wa-picker-panel");
const closeButton = modal?.querySelector(".wa-picker-close");
const directLinks = [...(modal?.querySelectorAll("[data-wa-direct]") || [])];
const triggerLinks = [...document.querySelectorAll('a[href^="https://wa.me/"]:not([data-wa-direct])')];
let lastFocused = null;

const updateAdminLinks = (href) => {
  const source = new URL(href, location.href);
  const message = source.searchParams.get("text");
  directLinks.forEach((link) => {
    const destination = new URL(`https://wa.me/${link.dataset.waNumber}`);
    if (message) destination.searchParams.set("text", message);
    link.href = destination.toString();
  });
};
const openPicker = (event) => {
  event.preventDefault();
  lastFocused = event.currentTarget;
  updateAdminLinks(event.currentTarget.href);
  modal?.classList.remove("hidden"); modal?.classList.add("flex");
  modal?.setAttribute("aria-hidden", "false"); closeButton?.focus();
};
const closePicker = () => {
  modal?.classList.add("hidden"); modal?.classList.remove("flex");
  modal?.setAttribute("aria-hidden", "true"); lastFocused?.focus?.();
};
triggerLinks.forEach((link) => link.addEventListener("click", openPicker));
closeButton?.addEventListener("click", closePicker);
modal?.addEventListener("click", (event) => { if (!panel?.contains(event.target)) closePicker(); });
window.addEventListener("keydown", (event) => { if (event.key === "Escape") closePicker(); });
directLinks.forEach((link) => link.addEventListener("click", () => {
  trackLead(link.textContent.trim()); closePicker();
}));

const hideLoader = () => {
  const loader = document.querySelector("#page-loader");
  if (!loader || loader.classList.contains("is-hiding")) return;
  loader.classList.add("is-hiding");
  setTimeout(() => loader.remove(), reducedMotion ? 0 : 650);
};
window.addEventListener("load", hideLoader, { once: true });
window.setTimeout(hideLoader, 1800);
