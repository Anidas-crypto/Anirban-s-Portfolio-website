// ── Nav toggle ──
const menuBtn = document.querySelector(".menu-btn");
const nav = document.getElementById("nav-menu");
menuBtn.addEventListener("click", () => nav.classList.toggle("active"));
document.addEventListener("click", (e) => {
    if (!nav.contains(e.target) && !menuBtn.contains(e.target))
        nav.classList.remove("active");
});

// ── Custom cursor ──
// Using CSS transform instead of top/left so position updates
// are handled entirely by the GPU compositor — zero layout cost.
const cursor = document.getElementById('cursor');
const ring   = document.getElementById('cursorRing');

let mx = 0, my = 0;
let rx = 0, ry = 0;
let rafId = null;

document.addEventListener('mousemove', e => {
    mx = e.clientX;
    my = e.clientY;
    // Dot moves instantly on the GPU
    cursor.style.transform = `translate(calc(${mx}px - 50%), calc(${my}px - 50%))`;
    // Kick off ring follow if not already running
    if (!rafId) rafId = requestAnimationFrame(followRing);
}, { passive: true });

function followRing() {
    rx += (mx - rx) * 0.12;
    ry += (my - ry) * 0.12;
    ring.style.transform = `translate(calc(${rx}px - 50%), calc(${ry}px - 50%))`;

    // Stop the loop when ring has caught up (saves idle CPU)
    if (Math.abs(mx - rx) > 0.5 || Math.abs(my - ry) > 0.5) {
        rafId = requestAnimationFrame(followRing);
    } else {
        rafId = null;
    }
}
