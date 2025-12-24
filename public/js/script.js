const $ = (q, root = document) => root.querySelector(q);
const $$ = (q, root = document) => [...root.querySelectorAll(q)];

const yearEl = $("#year");
if (yearEl) yearEl.textContent = new Date().getFullYear();

const overlay = $("#navOverlay");
const menuBtn = $("#menuBtn");
const closeBtn = $("#closeBtn");

function openOverlay() {
  if (!overlay) return;
  overlay.classList.add("is-open");
  overlay.setAttribute("aria-hidden", "false");
  menuBtn?.setAttribute("aria-expanded", "true");
  document.body.classList.add("nav-open");
}

function closeOverlay() {
  if (!overlay) return;
  overlay.classList.remove("is-open");
  overlay.setAttribute("aria-hidden", "true");
  menuBtn?.setAttribute("aria-expanded", "false");
  document.body.classList.remove("nav-open");
}

menuBtn?.addEventListener("click", openOverlay);
closeBtn?.addEventListener("click", closeOverlay);

overlay?.addEventListener("click", (e) => {
  if (e.target?.dataset?.close) closeOverlay();
});

$$(".navOverlay__link").forEach((a) => {
  a.addEventListener("click", () => closeOverlay());
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && overlay?.classList.contains("is-open")) closeOverlay();
});

const io = new IntersectionObserver(
  (entries) => {
    for (const ent of entries) {
      if (ent.isIntersecting) ent.target.classList.add("is-in");
    }
  },
  { threshold: 0.12 }
);
$$(".reveal").forEach((el) => io.observe(el));

function animateCounter(el, to) {
  const prefersReduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (prefersReduce) {
    el.textContent = String(to);
    return;
  }

  const from = 0;
  const dur = 900;
  const start = performance.now();

  const tick = (t) => {
    const p = Math.min(1, (t - start) / dur);
    const val = Math.floor(from + (to - from) * (1 - Math.pow(1 - p, 3)));
    el.textContent = String(val);
    if (p < 1) requestAnimationFrame(tick);
  };

  requestAnimationFrame(tick);
}

const counterIO = new IntersectionObserver(
  (entries, obs) => {
    for (const ent of entries) {
      if (!ent.isIntersecting) continue;
      const el = ent.target;
      const to = Number(el.getAttribute("data-counter") || "0");
      animateCounter(el, to);
      obs.unobserve(el);
    }
  },
  { threshold: 0.4 }
);
$$("[data-counter]").forEach((el) => counterIO.observe(el));

const track = $("#track");
const prevBtn = $("#prevBtn");
const nextBtn = $("#nextBtn");

function scrollByCard(dir = 1) {
  if (!track) return;
  const card = track.querySelector(".review");
  const amount = (card?.getBoundingClientRect().width || 380) + 12;
  track.scrollBy({ left: amount * dir, behavior: "smooth" });
}

prevBtn?.addEventListener("click", () => scrollByCard(-1));
nextBtn?.addEventListener("click", () => scrollByCard(1));

let autoTimer = null;

function startAuto() {
  stopAuto();
  autoTimer = setInterval(() => scrollByCard(1), 4500);
}

function stopAuto() {
  if (autoTimer) clearInterval(autoTimer);
  autoTimer = null;
}

track?.addEventListener("mouseenter", stopAuto);
track?.addEventListener("mouseleave", startAuto);
startAuto();

(() => {
  const items = document.querySelectorAll("[data-parallax]");
  if (!items.length) return;

  let raf = null;

  const update = () => {
    const vh = window.innerHeight;

    items.forEach((wrap) => {
      const img = wrap.querySelector("img");
      if (!img) return;

      const rect = wrap.getBoundingClientRect();

      if (rect.bottom < -200 || rect.top > vh + 200) return;

      const mid = rect.top + rect.height / 2;
      const p = (mid - vh / 2) / (vh / 2);
      const clamped = Math.max(-1, Math.min(1, p));

      const strength = Number(wrap.dataset.parallaxStrength || 18);
      const y = -clamped * strength;

      img.style.transform = `translateY(${y}px) scale(1.10)`;
    });

    raf = null;
  };

  const onScroll = () => {
    if (raf) return;
    raf = requestAnimationFrame(update);
  };

  update();
  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", update);
})();

(() => {
  const root = document.querySelector("[data-services]");
  if (!root) return;

  const jsonEl = root.querySelector("[data-services-json]");
  if (!jsonEl) return;

  const items = JSON.parse(jsonEl.textContent || "[]");
  if (!items.length) return;

  const titleEl = root.querySelector("[data-services-title]");
  const descEl  = root.querySelector("[data-services-desc]");
  const imgEl   = root.querySelector("[data-services-img]");
  const numEl   = root.querySelector("[data-services-num]");
  const tagsWrap= root.querySelector("[data-services-tags]");
  const prevBtn = root.querySelector("[data-services-prev]");
  const nextBtn = root.querySelector("[data-services-next]");
  const dots    = Array.from(root.querySelectorAll("[data-services-dot]"));

  let idx = 0;

  const pad2 = (n) => String(n).padStart(2, "0");

  const render = (i) => {
    idx = (i + items.length) % items.length;
    const item = items[idx];

    root.classList.add("is-swapping");
    setTimeout(() => {
      titleEl.textContent = item.title;
      descEl.textContent  = item.desc;
      imgEl.src = item.img;
      imgEl.alt = item.title;
      numEl.textContent = pad2(idx + 1);

      tagsWrap.innerHTML = item.tags
        .map(t => `<span class="servicesShow__tag">${t}</span>`)
        .join("");

      dots.forEach(d => d.classList.toggle("is-active", Number(d.dataset.servicesDot) === idx));
      root.classList.remove("is-swapping");
    }, 120);
  };

  prevBtn?.addEventListener("click", () => render(idx - 1));
  nextBtn?.addEventListener("click", () => render(idx + 1));

  dots.forEach(d => {
    d.addEventListener("click", () => render(Number(d.dataset.servicesDot)));
  });

  render(0);
})();

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.querySelector(".siteFooter__toTop");
  if (!btn) return;

  btn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('[data-to-top]');
  if (!btn) return;

  const progress = btn.querySelector('.toTop__progress');
  if (!progress) return;

  const scroller = document.scrollingElement || document.documentElement;

  const r = 46;
  const c = 2 * Math.PI * r;

  progress.style.strokeDasharray = `${c}`;
  progress.style.strokeDashoffset = `${c}`;

  const clamp01 = (n) => Math.min(1, Math.max(0, n));

  const getScrollTop = () => {

    const winY = window.scrollY || 0;

    const docY = scroller.scrollTop || 0;

    return winY || docY;
  };

  const getMaxScroll = () => {
    const docMax = (scroller.scrollHeight - window.innerHeight) || 1;
    return docMax;
  };

  const update = () => {
    const scrollTop = getScrollTop();
    const max = getMaxScroll();

    const p = clamp01(scrollTop / max);
    progress.style.strokeDashoffset = `${c * (1 - p)}`;

    btn.classList.toggle('is-visible', scrollTop > 240);
  };

  update();

  window.addEventListener('scroll', update, { passive: true });
  document.addEventListener('scroll', update, { passive: true, capture: true });
  window.addEventListener('resize', update);

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});

(() => {
  const dot = document.querySelector(".cursorDot");
  const ring = document.querySelector(".cursorRing");
  if (!dot || !ring) return;

  const isTouch = window.matchMedia("(hover: none), (pointer: coarse)").matches;
  if (isTouch) return;

  document.body.classList.add("cursor-ready");

  let targetX = window.innerWidth / 2;
  let targetY = window.innerHeight / 2;
  let ringX = targetX, ringY = targetY;

  const dotSpeed = 0.35;
  const ringSpeed = 0.14;

  let dotX = targetX, dotY = targetY;

  window.addEventListener("mousemove", (e) => {
    targetX = e.clientX;
    targetY = e.clientY;
  });

  const hoverables = "a, button, [role='button'], input, textarea, select, .btn";
  document.addEventListener("mouseover", (e) => {
    if (e.target.closest(hoverables)) document.body.classList.add("cursor-hover");
  });
  document.addEventListener("mouseout", (e) => {
    if (e.target.closest(hoverables)) document.body.classList.remove("cursor-hover");
  });

  document.addEventListener("mousedown", () => document.body.classList.add("cursor-down"));
  document.addEventListener("mouseup", () => document.body.classList.remove("cursor-down"));

  function animate() {
    dotX += (targetX - dotX) * dotSpeed;
    dotY += (targetY - dotY) * dotSpeed;

    ringX += (targetX - ringX) * ringSpeed;
    ringY += (targetY - ringY) * ringSpeed;

    dot.style.left = dotX + "px";
    dot.style.top = dotY + "px";

    ring.style.left = ringX + "px";
    ring.style.top = ringY + "px";

    requestAnimationFrame(animate);
  }
  animate();

  window.addEventListener("mouseleave", () => document.body.classList.remove("cursor-ready"));
  window.addEventListener("mouseenter", () => document.body.classList.add("cursor-ready"));
})();
