// ── Nav toggle ──
    const menuBtn = document.querySelector(".menu-btn");
    const nav = document.getElementById("nav-menu");
    menuBtn.addEventListener("click", () => nav.classList.toggle("active"));
    document.addEventListener("click", (e) => {
        if (!nav.contains(e.target) && !menuBtn.contains(e.target))
            nav.classList.remove("active");
    });

    // ── Custom cursor: dot snaps to mouse, ring follows with lag ──
    const cursor = document.getElementById('cursor');
    const ring   = document.getElementById('cursorRing');
    let mx = 0, my = 0, rx = 0, ry = 0;

    document.addEventListener('mousemove', e => {
        mx = e.clientX;
        my = e.clientY;
        // Dot moves instantly
        cursor.style.left = mx + 'px';
        cursor.style.top  = my + 'px';
    });

    // Ring follows with smooth lag via rAF
    function followRing() {
        rx += (mx - rx) * 0.12;
        ry += (my - ry) * 0.12;
        ring.style.left = rx + 'px';
        ring.style.top  = ry + 'px';
        requestAnimationFrame(followRing);
    }
    followRing();