(() => {
  const dots = Array.from(document.querySelectorAll(".cursorDot"));
  const rings = Array.from(document.querySelectorAll(".cursorRing"));

  // Hapus semua duplikat, sisakan 1 saja
  dots.slice(0, -1).forEach((el) => el.remove());
  rings.slice(0, -1).forEach((el) => el.remove());
})();


(() => {
  const dot = document.querySelector(".cursorDot");
  const ring = document.querySelector(".cursorRing");
  if (!dot || !ring) return;

  // Desktop pointer only
  const canHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  if (!canHover) return;

  let x = window.innerWidth / 2;
  let y = window.innerHeight / 2;
  let rx = x, ry = y; // ring position (smoothed)
  let targetX = x, targetY = y;

  const ease = 0.14; // semakin kecil semakin lambat

  const setPos = (el, px, py) => {
    el.style.transform = `translate(${px}px, ${py}px) translate(-50%, -50%)`;
  };

  const onMove = (e) => {
    targetX = e.clientX;
    targetY = e.clientY;
    document.body.classList.add("has-cursor"); // baru munculin setelah gerak
    setPos(dot, targetX, targetY);
  };

  const tick = () => {
    rx += (targetX - rx) * ease;
    ry += (targetY - ry) * ease;
    setPos(ring, rx, ry);
    requestAnimationFrame(tick);
  };

  window.addEventListener("mousemove", onMove, { passive: true });

  window.addEventListener("mousedown", () => document.body.classList.add("is-clicking"));
  window.addEventListener("mouseup", () => document.body.classList.remove("is-clicking"));

  requestAnimationFrame(tick);
})();
