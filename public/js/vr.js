/* ══════════════════════════════════════════════════════════
   vr.js — LifeShield XR Scene Controller + Crash Engine
   ══════════════════════════════════════════════════════════ */

/* ── State ─────────────────────────────────────────────── */
let currentScene = 1;
const totalScenes = 7;
const sceneTitles = [
    'Welcome Home', 'Accident Occurs', 'Bills Rising',
    'No Insurance',  'Insurance Applied', 'Family Secure', 'Take Action'
];

/* ── Init ──────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    initCursor();
    initParticles();
    initDots();
    updateUI();
    triggerSceneAnimations(1);
});

/* ── Cursor ─────────────────────────────────────────────── */
function initCursor() {
    const dot  = document.getElementById('cursor-dot');
    const ring = document.getElementById('cursor-ring');
    let mx = 0, my = 0, rx = 0, ry = 0;
    document.addEventListener('mousemove', e => {
        mx = e.clientX; my = e.clientY;
        dot.style.left = mx + 'px'; dot.style.top = my + 'px';
    });
    const animRing = () => {
        rx += (mx - rx) * 0.14; ry += (my - ry) * 0.14;
        ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
        requestAnimationFrame(animRing);
    };
    animRing();
    document.querySelectorAll('button, a, .cta-card, .scene-dot').forEach(el => {
        el.addEventListener('mouseenter', () => { ring.style.width='50px'; ring.style.height='50px'; ring.style.borderColor='rgba(0,240,255,0.7)'; });
        el.addEventListener('mouseleave', () => { ring.style.width='36px'; ring.style.height='36px'; ring.style.borderColor='rgba(0,240,255,0.5)'; });
    });
}

/* ── Dots ───────────────────────────────────────────────── */
function initDots() {
    const container = document.getElementById('scene-dots');
    container.innerHTML = '';
    for (let i = 1; i <= totalScenes; i++) {
        const d = document.createElement('div');
        d.className = 'scene-dot' + (i === 1 ? ' active' : '');
        d.title = sceneTitles[i-1];
        d.onclick = () => goToScene(i);
        container.appendChild(d);
    }
}

function updateDots() {
    document.querySelectorAll('.scene-dot').forEach((d, i) => {
        d.className = 'scene-dot';
        if (i + 1 <  currentScene) d.classList.add('done');
        if (i + 1 === currentScene) d.classList.add('active');
    });
}

/* ── Scene Navigation ───────────────────────────────────── */
function changeScene(dir) {
    const next = currentScene + dir;
    if (next < 1 || next > totalScenes) return;
    goToScene(next);
}

function goToScene(n) {
    if (n === currentScene) return;
    flash(() => {
        document.getElementById('scene-' + currentScene).classList.remove('active');
        currentScene = n;
        document.getElementById('scene-' + n).classList.add('active');
        updateUI();
        triggerSceneAnimations(n);
    });
}

function flash(cb) {
    const f = document.getElementById('flash-overlay');
    f.style.opacity = '0.12';
    setTimeout(() => { f.style.opacity = '0'; cb(); }, 100);
}

function updateUI() {
    document.getElementById('xr-progress-fill').style.width = ((currentScene / totalScenes) * 100) + '%';
    document.getElementById('btn-prev').disabled = currentScene === 1;
    const nextBtn = document.getElementById('btn-next');
    if (currentScene === 4) {
        nextBtn.textContent = 'See With Insurance →';
        nextBtn.className = 'ctrl-btn success';
        nextBtn.onclick = () => changeScene(1);
    } else if (currentScene === totalScenes) {
        nextBtn.textContent = '↺ Replay';
        nextBtn.className = 'ctrl-btn';
        nextBtn.onclick = () => goToScene(1);
    } else {
        nextBtn.textContent = 'Next →';
        nextBtn.className = 'ctrl-btn primary';
        nextBtn.onclick = () => changeScene(1);
    }
    document.getElementById('current-scene-num').textContent = currentScene;
    document.getElementById('scene-title-hud').textContent = sceneTitles[currentScene - 1];
    updateDots();
}

/* ── Scene-specific Triggers ────────────────────────────── */
function triggerSceneAnimations(n) {
    if (n === 2) initCrashScene();
    if (n === 3) startCounters();
    if (n === 5) {
        const s5 = document.getElementById('scene-5');
        s5.classList.remove('insurance-applied');
        document.getElementById('before-insurance').style.display = 'block';
        document.getElementById('after-insurance').style.display = 'none';
        document.getElementById('coverage-fill').style.width = '0%';
    }
}

/* ── Counter Animation ──────────────────────────────────── */
function startCounters() {
    document.querySelectorAll('.counter').forEach(el => {
        const target = parseInt(el.dataset.target);
        const delay  = parseFloat(el.closest('.bill-card')?.style.animationDelay || '0') * 1000 + 300;
        setTimeout(() => {
            const start = performance.now();
            const step = ts => {
                const p = Math.min((ts - start) / 1400, 1);
                const ease = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.floor(ease * target).toLocaleString('en-IN');
                if (p < 1) requestAnimationFrame(step);
                else el.textContent = target.toLocaleString('en-IN');
            };
            requestAnimationFrame(step);
        }, delay);
    });
}

/* ── Apply Insurance ────────────────────────────────────── */
function applyInsurance() {
    flash(() => {
        document.getElementById('before-insurance').style.display = 'none';
        document.getElementById('after-insurance').style.display  = 'flex';
        document.getElementById('scene-5').classList.add('insurance-applied');
        setTimeout(() => { document.getElementById('coverage-fill').style.width = '80%'; }, 1300);
    });
}

/* ── Background Particles ───────────────────────────────── */
function initParticles() {
    const canvas = document.getElementById('particle-canvas');
    const ctx    = canvas.getContext('2d');
    let W, H;

    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    window.addEventListener('resize', resize);
    resize();

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x = Math.random() * W; this.y = Math.random() * H;
            this.r = Math.random() * 1.5 + 0.3;
            this.vx = (Math.random() - 0.5) * 0.25; this.vy = (Math.random() - 0.5) * 0.25;
            this.alpha = Math.random() * 0.4 + 0.1;
            this.color = Math.random() > 0.6 ? '#00F0FF' : Math.random() > 0.5 ? '#8B5CF6' : '#ffffff';
        }
        update() { this.x += this.vx; this.y += this.vy; if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset(); }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2); ctx.fillStyle = this.color; ctx.globalAlpha = this.alpha; ctx.fill(); }
    }

    const particles = Array.from({ length: 90 }, () => new Particle());

    function loop() {
        ctx.clearRect(0, 0, W, H); ctx.globalAlpha = 1;
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x, dy = particles[i].y - particles[j].y;
                const d = Math.sqrt(dx*dx + dy*dy);
                if (d < 100) {
                    ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = 'rgba(0,240,255,' + (0.04*(1-d/100)) + ')'; ctx.lineWidth = 0.5; ctx.stroke();
                }
            }
        }
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(loop);
    }
    loop();
}

/* ── Keyboard ───────────────────────────────────────────── */
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight' || e.key === ' ') changeScene(1);
    if (e.key === 'ArrowLeft') changeScene(-1);
});


/* ══════════════════════════════════════════════════════════
   CRASH ANIMATION ENGINE — Scene 2
   ══════════════════════════════════════════════════════════ */

let crashRAF = null;      // animation frame handle
let crashPhase = 'idle';  // state machine
let crashT = 0;           // elapsed ms within phase
let crashLastTs = null;
let sparkPool = [];
let debrisPool = [];
let firePool = [];
let rainDrops = [];
let smokePool = [];
let ambulanceX = 0;
let crashComplete = false;

/* ── Canvas setup ───────────────────────────────────────── */
let CC, ctx2;
let CW, CH;

function initCrashScene() {
    // Cancel any previous animation
    if (crashRAF) { cancelAnimationFrame(crashRAF); crashRAF = null; }

    CC  = document.getElementById('crash-canvas');
    if (!CC) return;
    ctx2 = CC.getContext('2d');

    resizeCrash();
    window.addEventListener('resize', resizeCrash);

    // Reset HUD
    const chip = document.getElementById('crash-chip');
    const statsRow = document.getElementById('crash-stats-row');
    const titleWrap = document.getElementById('crash-title-wrap');
    const narration = document.getElementById('crash-narration');
    const hudSpeed = document.getElementById('hud-speed');
    const hudImpact = document.getElementById('hud-impact');
    const hudSos   = document.getElementById('hud-sos');

    chip.style.opacity       = '0';
    statsRow.style.opacity   = '0';
    titleWrap.style.opacity  = '0';
    narration.style.opacity  = '0';
    hudSpeed.style.opacity   = '1';
    hudImpact.style.opacity  = '0';
    hudSos.style.opacity     = '0';

    // Reset state
    crashPhase    = 'approach';
    crashT        = 0;
    crashLastTs   = null;
    crashComplete = false;
    sparkPool     = [];
    debrisPool    = [];
    firePool      = [];
    smokePool     = [];

    initRain();
    initAmbulance();

    // Show chip
    setTimeout(() => { chip.style.transition = 'opacity 0.5s'; chip.style.opacity = '1'; }, 300);

    crashRAF = requestAnimationFrame(crashLoop);
}

function resizeCrash() {
    if (!CC) return;
    CW = CC.width  = CC.offsetWidth;
    CH = CC.height = CC.offsetHeight;
}

/* ── Rain ───────────────────────────────────────────────── */
function initRain() {
    rainDrops = [];
    for (let i = 0; i < 140; i++) {
        rainDrops.push({
            x: Math.random() * 1400,
            y: Math.random() * 900,
            len: 10 + Math.random() * 18,
            speed: 5 + Math.random() * 9,
            alpha: 0.08 + Math.random() * 0.2
        });
    }
}

function drawRain() {
    ctx2.save();
    ctx2.strokeStyle = 'rgba(160,200,255,0.55)';
    ctx2.lineWidth = 0.6;
    rainDrops.forEach(d => {
        ctx2.globalAlpha = d.alpha;
        ctx2.beginPath();
        ctx2.moveTo(d.x, d.y);
        ctx2.lineTo(d.x + d.len * 0.15, d.y + d.len);
        ctx2.stroke();
        d.y += d.speed; d.x += d.speed * 0.15;
        if (d.y > CH + 20) { d.y = -20; d.x = Math.random() * CW; }
    });
    ctx2.restore();
}

/* ── Ambulance ──────────────────────────────────────────── */
function initAmbulance() { ambulanceX = -200; }

/* ── Car geometry helpers ───────────────────────────────── */

// Draw a city car (side view, facing right by default)
// dir: 1=right, -1=left
function drawCar(x, y, w, h, bodyColor, accentColor, dir, crumple, headlightOn) {
    ctx2.save();
    if (dir === -1) { ctx2.translate(x + w, y); ctx2.scale(-1, 1); x = 0; y = 0; } else { ctx2.translate(0, 0); }

    const rx = (dir === -1) ? 0 : x;

    // Shadow
    ctx2.save();
    ctx2.globalAlpha = 0.3;
    ctx2.fillStyle = 'rgba(0,0,0,0.5)';
    ctx2.beginPath();
    ctx2.ellipse(rx + w/2, y + h + 4, w * 0.45, 8, 0, 0, Math.PI * 2);
    ctx2.fill();
    ctx2.restore();

    // Body
    ctx2.fillStyle = bodyColor;
    ctx2.beginPath();
    // Crumple factor squashes the left/right
    const bx = rx + (crumple > 0 ? crumple * 18 : 0);
    const bw = w - (crumple > 0 ? crumple * 18 : 0);
    ctx2.roundRect(bx, y + h * 0.35, bw, h * 0.55, [4, 4, 4, 4]);
    ctx2.fill();

    // Roof / cabin
    ctx2.fillStyle = bodyColor;
    ctx2.beginPath();
    ctx2.moveTo(rx + w * 0.2, y + h * 0.35);
    ctx2.lineTo(rx + w * 0.28, y + h * 0.05);
    ctx2.lineTo(rx + w * 0.72, y + h * 0.05);
    ctx2.lineTo(rx + w * 0.82, y + h * 0.35);
    ctx2.closePath();
    ctx2.fill();

    // Windows
    ctx2.fillStyle = 'rgba(160,210,255,0.18)';
    ctx2.strokeStyle = 'rgba(100,180,255,0.3)';
    ctx2.lineWidth = 1;

    // Front window
    ctx2.beginPath();
    ctx2.moveTo(rx + w * 0.58, y + h * 0.1);
    ctx2.lineTo(rx + w * 0.72, y + h * 0.1);
    ctx2.lineTo(rx + w * 0.8,  y + h * 0.34);
    ctx2.lineTo(rx + w * 0.59, y + h * 0.34);
    ctx2.closePath();
    ctx2.fill(); ctx2.stroke();

    // Rear window
    ctx2.beginPath();
    ctx2.moveTo(rx + w * 0.28, y + h * 0.1);
    ctx2.lineTo(rx + w * 0.54, y + h * 0.1);
    ctx2.lineTo(rx + w * 0.56, y + h * 0.34);
    ctx2.lineTo(rx + w * 0.28, y + h * 0.34);
    ctx2.closePath();
    ctx2.fill(); ctx2.stroke();

    // Door line
    ctx2.strokeStyle = 'rgba(0,0,0,0.25)'; ctx2.lineWidth = 1.5;
    ctx2.beginPath(); ctx2.moveTo(rx + w*0.56, y + h*0.35); ctx2.lineTo(rx + w*0.56, y + h*0.9); ctx2.stroke();

    // Wheels
    const wy = y + h * 0.88;
    [rx + w * 0.22, rx + w * 0.72].forEach(wx => {
        const r = h * 0.22;
        // Tyre
        ctx2.fillStyle = '#0A0C14';
        ctx2.beginPath(); ctx2.arc(wx, wy, r, 0, Math.PI * 2); ctx2.fill();
        // Rim
        ctx2.fillStyle = '#2A3A5A';
        ctx2.beginPath(); ctx2.arc(wx, wy, r * 0.62, 0, Math.PI * 2); ctx2.fill();
        // Spoke
        for (let s = 0; s < 5; s++) {
            const a = (s / 5) * Math.PI * 2;
            ctx2.strokeStyle = '#4A6A8A'; ctx2.lineWidth = 1.5;
            ctx2.beginPath(); ctx2.moveTo(wx, wy); ctx2.lineTo(wx + Math.cos(a)*r*0.55, wy + Math.sin(a)*r*0.55); ctx2.stroke();
        }
        ctx2.strokeStyle = '#1A2A4A'; ctx2.lineWidth = 2;
        ctx2.beginPath(); ctx2.arc(wx, wy, r, 0, Math.PI * 2); ctx2.stroke();
    });

    // Headlight (right side of car)
    if (headlightOn) {
        // Glow
        const grad = ctx2.createRadialGradient(rx+w-8, y+h*0.52, 0, rx+w-8, y+h*0.52, 60);
        grad.addColorStop(0, 'rgba(255,240,180,0.5)');
        grad.addColorStop(1, 'rgba(255,240,180,0)');
        ctx2.fillStyle = grad;
        ctx2.beginPath(); ctx2.ellipse(rx+w+20, y+h*0.52, 80, 32, 0, 0, Math.PI*2); ctx2.fill();
        // Lens
        ctx2.fillStyle = '#FFE08A';
        ctx2.beginPath(); ctx2.roundRect(rx+w-12, y+h*0.44, 14, 16, 3); ctx2.fill();
    }

    // Tail light
    ctx2.fillStyle = '#FF3B6B';
    ctx2.globalAlpha = 0.85;
    ctx2.beginPath(); ctx2.roundRect(rx+2, y+h*0.5, 10, 12, 2); ctx2.fill();
    ctx2.globalAlpha = 1;

    ctx2.restore();
}

// Draw truck (big vehicle, coming from right, facing left)
function drawTruck(x, y, w, h, crumple) {
    ctx2.save();

    // Shadow
    ctx2.fillStyle = 'rgba(0,0,0,0.4)';
    ctx2.beginPath(); ctx2.ellipse(x+w/2, y+h+6, w*0.45, 10, 0, 0, Math.PI*2); ctx2.fill();

    // Trailer body
    ctx2.fillStyle = '#1A1214';
    ctx2.beginPath(); ctx2.roundRect(x + (crumple>0?crumple*22:0), y+h*0.15, w*0.68 - (crumple>0?crumple*22:0), h*0.72, 4); ctx2.fill();
    ctx2.strokeStyle = '#3A2020'; ctx2.lineWidth = 1.5;
    ctx2.stroke();

    // Warning stripes on trailer
    for (let s = 0; s < 5; s++) {
        ctx2.fillStyle = s%2===0 ? 'rgba(255,183,0,0.4)' : 'rgba(255,59,107,0.3)';
        ctx2.fillRect(x + s*14 + (crumple>0?crumple*22:0), y+h*0.82, 14, h*0.05);
    }

    // Cab (right side, faces left so cab is on right)
    const cabX = x + w * 0.68;
    const crumpledCabW = w * 0.32 * (crumple>0 ? Math.max(0.4, 1-crumple*0.6) : 1);
    ctx2.fillStyle = '#2A1010';
    ctx2.beginPath(); ctx2.roundRect(cabX, y+h*0.1, crumpledCabW, h*0.77, [0,4,4,0]); ctx2.fill();
    ctx2.strokeStyle = '#4A2020'; ctx2.lineWidth = 1.5; ctx2.stroke();

    // Cab window
    ctx2.fillStyle = 'rgba(255,80,80,0.12)';
    ctx2.strokeStyle = 'rgba(255,80,80,0.25)'; ctx2.lineWidth = 1;
    ctx2.beginPath(); ctx2.roundRect(cabX+6, y+h*0.15, crumpledCabW-12, h*0.3, 3); ctx2.fill(); ctx2.stroke();

    // Headlights (left side of truck = front when facing left)
    ctx2.fillStyle = '#FFB700'; ctx2.globalAlpha = 0.9;
    ctx2.beginPath(); ctx2.roundRect(x + (crumple>0?crumple*10:0), y+h*0.22, 12, 14, 3); ctx2.fill();
    ctx2.beginPath(); ctx2.roundRect(x + (crumple>0?crumple*10:0), y+h*0.52, 12, 14, 3); ctx2.fill();
    ctx2.globalAlpha = 1;

    // Grill
    ctx2.strokeStyle = '#3A1010'; ctx2.lineWidth = 2;
    for (let g = 0; g < 4; g++) {
        ctx2.beginPath(); ctx2.moveTo(x+(crumple>0?crumple*10:4), y+h*(0.32+g*0.06)); ctx2.lineTo(x+(crumple>0?crumple*10:4)+18, y+h*(0.32+g*0.06)); ctx2.stroke();
    }

    // Wheels
    const wy = y + h * 0.88;
    [x+w*0.12, x+w*0.42, x+w*0.8].forEach(wx => {
        const r = h * 0.24;
        ctx2.fillStyle = '#080A10'; ctx2.beginPath(); ctx2.arc(wx, wy, r, 0, Math.PI*2); ctx2.fill();
        ctx2.fillStyle = '#2A1A1A'; ctx2.beginPath(); ctx2.arc(wx, wy, r*0.6, 0, Math.PI*2); ctx2.fill();
        for (let s = 0; s < 6; s++) {
            const a = (s/6)*Math.PI*2; ctx2.strokeStyle='#4A2A2A'; ctx2.lineWidth=1.5;
            ctx2.beginPath(); ctx2.moveTo(wx,wy); ctx2.lineTo(wx+Math.cos(a)*r*0.52, wy+Math.sin(a)*r*0.52); ctx2.stroke();
        }
        ctx2.strokeStyle='#1A0A0A'; ctx2.lineWidth=2.5; ctx2.beginPath(); ctx2.arc(wx,wy,r,0,Math.PI*2); ctx2.stroke();
    });

    ctx2.restore();
}

// Draw person (stick figure, for ejection)
function drawPerson(x, y, angle, alpha) {
    ctx2.save();
    ctx2.globalAlpha = alpha;
    ctx2.translate(x, y);
    ctx2.rotate(angle);
    ctx2.strokeStyle = '#E8C9A0'; ctx2.lineWidth = 3; ctx2.lineCap = 'round';
    // Head
    ctx2.fillStyle = '#E8C9A0';
    ctx2.beginPath(); ctx2.arc(0, -20, 8, 0, Math.PI*2); ctx2.fill();
    // Body
    ctx2.beginPath(); ctx2.moveTo(0,-12); ctx2.lineTo(0,12); ctx2.stroke();
    // Arms
    ctx2.strokeStyle = '#1E3A5F';
    ctx2.beginPath(); ctx2.moveTo(-14,2); ctx2.lineTo(14,-2); ctx2.stroke();
    // Legs
    ctx2.beginPath(); ctx2.moveTo(0,12); ctx2.lineTo(-10,28); ctx2.stroke();
    ctx2.beginPath(); ctx2.moveTo(0,12); ctx2.lineTo(10,28); ctx2.stroke();
    ctx2.restore();
}

// Draw ambulance
function drawAmbulance(x, y, w, h, lightPhase) {
    ctx2.save();

    ctx2.fillStyle = '#E8EDF5';
    ctx2.beginPath(); ctx2.roundRect(x, y, w, h, 4); ctx2.fill();

    // Red cross
    ctx2.fillStyle = '#FF3B6B'; ctx2.globalAlpha = 0.85;
    ctx2.fillRect(x+w*0.35+6, y+h*0.25, 8, 22);
    ctx2.fillRect(x+w*0.35, y+h*0.25+7, 22, 8);
    ctx2.globalAlpha = 1;

    // Emergency lights
    const lOn = Math.sin(lightPhase * 8) > 0;
    ctx2.fillStyle = lOn ? '#FF3B6B' : '#CC0000';
    ctx2.globalAlpha = lOn ? 0.95 : 0.4;
    ctx2.beginPath(); ctx2.roundRect(x+8, y-8, 20, 10, 3); ctx2.fill();
    ctx2.fillStyle = lOn ? '#0050FF' : '#0030AA';
    ctx2.globalAlpha = lOn ? 0.95 : 0.4;
    ctx2.beginPath(); ctx2.roundRect(x+36, y-8, 20, 10, 3); ctx2.fill();
    ctx2.globalAlpha = 1;

    // Headlights
    ctx2.fillStyle = '#FFE06A'; ctx2.globalAlpha = 0.9;
    ctx2.beginPath(); ctx2.roundRect(x+w-14, y+h*0.3, 16, 10, 2); ctx2.fill();
    ctx2.globalAlpha = 1;

    // Siren glow
    if (lOn) {
        const sg = ctx2.createRadialGradient(x+w+10, y+h*0.35, 0, x+w+10, y+h*0.35, 50);
        sg.addColorStop(0,'rgba(255,59,107,0.4)'); sg.addColorStop(1,'rgba(255,59,107,0)');
        ctx2.fillStyle = sg;
        ctx2.beginPath(); ctx2.ellipse(x+w+10, y+h*0.35, 60, 30, 0, 0, Math.PI*2); ctx2.fill();
    }

    // Wheels
    [x+w*0.22, x+w*0.78].forEach(wx => {
        const r = h * 0.22;
        ctx2.fillStyle='#222'; ctx2.beginPath(); ctx2.arc(wx,y+h+r-4,r,0,Math.PI*2); ctx2.fill();
        ctx2.fillStyle='#444'; ctx2.beginPath(); ctx2.arc(wx,y+h+r-4,r*0.55,0,Math.PI*2); ctx2.fill();
        ctx2.strokeStyle='#333'; ctx2.lineWidth=2; ctx2.beginPath(); ctx2.arc(wx,y+h+r-4,r,0,Math.PI*2); ctx2.stroke();
    });

    ctx2.restore();
}

/* ── Particle helpers ───────────────────────────────────── */
function spawnSparks(cx, cy, count) {
    for (let i = 0; i < count; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = 4 + Math.random() * 14;
        const colors = ['#FFB700','#FF6B00','#FF3B6B','#FFE066','#FFFFFF','#00F0FF'];
        sparkPool.push({
            x: cx, y: cy,
            vx: Math.cos(angle)*speed, vy: Math.sin(angle)*speed - Math.random()*6,
            r: 1.5 + Math.random()*3.5,
            color: colors[Math.floor(Math.random()*colors.length)],
            alpha: 1, decay: 0.016 + Math.random()*0.03,
            gravity: 0.18 + Math.random()*0.28
        });
    }
}

function spawnDebris(cx, cy, count) {
    for (let i = 0; i < count; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = 2 + Math.random() * 10;
        debrisPool.push({
            x: cx, y: cy,
            vx: Math.cos(angle)*speed, vy: Math.sin(angle)*speed - Math.random()*8,
            w: 3 + Math.random()*14, h: 3 + Math.random()*10,
            rot: Math.random()*Math.PI*2, rotV: (Math.random()-0.5)*0.4,
            color: ['#444','#666','#2A3A5A','#FFB700','#888'][Math.floor(Math.random()*5)],
            alpha: 1, decay: 0.008 + Math.random()*0.015,
            gravity: 0.25 + Math.random()*0.3
        });
    }
}

function spawnSmoke(cx, cy) {
    for (let i = 0; i < 3; i++) {
        smokePool.push({
            x: cx + (Math.random()-0.5)*30, y: cy,
            vx: (Math.random()-0.5)*0.8, vy: -(1+Math.random()*1.5),
            r: 8 + Math.random()*14,
            alpha: 0.4 + Math.random()*0.3,
            decay: 0.006 + Math.random()*0.008,
            grow: 0.5 + Math.random()*0.8
        });
    }
}

function spawnFire(cx, cy) {
    for (let i = 0; i < 4; i++) {
        const colors = ['#FF6B00','#FFB700','#FF3B6B','#FF9500','#FFDD00'];
        firePool.push({
            x: cx + (Math.random()-0.5)*20, y: cy,
            vx: (Math.random()-0.5)*1.2, vy: -(2+Math.random()*3),
            r: 4 + Math.random()*10,
            color: colors[Math.floor(Math.random()*colors.length)],
            alpha: 0.8 + Math.random()*0.2,
            decay: 0.02 + Math.random()*0.03
        });
    }
}

function updateParticles() {
    sparkPool = sparkPool.filter(p => p.alpha > 0.01);
    sparkPool.forEach(p => {
        p.x += p.vx; p.y += p.vy; p.vy += p.gravity;
        p.vx *= 0.97; p.alpha -= p.decay; p.r *= 0.985;
    });
    debrisPool = debrisPool.filter(p => p.alpha > 0.01);
    debrisPool.forEach(p => {
        p.x += p.vx; p.y += p.vy; p.vy += p.gravity;
        p.vx *= 0.97; p.rot += p.rotV; p.alpha -= p.decay;
    });
    smokePool = smokePool.filter(p => p.alpha > 0.005);
    smokePool.forEach(p => {
        p.x += p.vx; p.y += p.vy; p.r += p.grow; p.alpha -= p.decay;
    });
    firePool = firePool.filter(p => p.alpha > 0.01);
    firePool.forEach(p => {
        p.x += p.vx; p.y += p.vy; p.r *= 0.94; p.alpha -= p.decay;
    });
}

function drawParticles() {
    // Sparks
    sparkPool.forEach(p => {
        ctx2.save(); ctx2.globalAlpha = p.alpha;
        ctx2.fillStyle = p.color;
        ctx2.beginPath(); ctx2.arc(p.x, p.y, p.r, 0, Math.PI*2); ctx2.fill();
        // Trail
        ctx2.globalAlpha = p.alpha * 0.25;
        ctx2.strokeStyle = p.color; ctx2.lineWidth = p.r*0.6;
        ctx2.beginPath(); ctx2.moveTo(p.x, p.y); ctx2.lineTo(p.x - p.vx*3, p.y - p.vy*3); ctx2.stroke();
        ctx2.restore();
    });
    // Debris
    debrisPool.forEach(p => {
        ctx2.save(); ctx2.globalAlpha = p.alpha;
        ctx2.translate(p.x, p.y); ctx2.rotate(p.rot);
        ctx2.fillStyle = p.color;
        ctx2.fillRect(-p.w/2, -p.h/2, p.w, p.h);
        ctx2.restore();
    });
    // Smoke
    smokePool.forEach(p => {
        ctx2.save(); ctx2.globalAlpha = p.alpha;
        const sg = ctx2.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
        sg.addColorStop(0, 'rgba(100,100,110,0.9)'); sg.addColorStop(1, 'rgba(60,60,70,0)');
        ctx2.fillStyle = sg;
        ctx2.beginPath(); ctx2.arc(p.x, p.y, p.r, 0, Math.PI*2); ctx2.fill();
        ctx2.restore();
    });
    // Fire
    firePool.forEach(p => {
        ctx2.save(); ctx2.globalAlpha = p.alpha;
        const fg = ctx2.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
        fg.addColorStop(0, '#FFF'); fg.addColorStop(0.3, p.color); fg.addColorStop(1, 'transparent');
        ctx2.fillStyle = fg;
        ctx2.beginPath(); ctx2.arc(p.x, p.y, p.r, 0, Math.PI*2); ctx2.fill();
        ctx2.restore();
    });
}

/* ── Draw road environment ──────────────────────────────── */
function drawEnvironment(t, shake) {
    const shakeX = shake * (Math.random()-0.5)*18;
    const shakeY = shake * (Math.random()-0.5)*12;
    ctx2.save();
    if (shake > 0) ctx2.translate(shakeX, shakeY);

    // Night sky gradient
    const skyGrad = ctx2.createLinearGradient(0, 0, 0, CH * 0.55);
    skyGrad.addColorStop(0, '#020408');
    skyGrad.addColorStop(0.5, '#06080F');
    skyGrad.addColorStop(1, '#0C0A12');
    ctx2.fillStyle = skyGrad;
    ctx2.fillRect(0, 0, CW, CH * 0.55);

    // Moon
    const moonX = CW * 0.82, moonY = CH * 0.12;
    const moonGrad = ctx2.createRadialGradient(moonX-8, moonY-8, 0, moonX, moonY, 36);
    moonGrad.addColorStop(0, '#E8EDF5'); moonGrad.addColorStop(1, '#8090A0');
    ctx2.fillStyle = moonGrad;
    ctx2.beginPath(); ctx2.arc(moonX, moonY, 28, 0, Math.PI*2); ctx2.fill();
    ctx2.fillStyle = 'rgba(200,220,255,0.12)';
    ctx2.beginPath(); ctx2.arc(moonX, moonY, 50, 0, Math.PI*2); ctx2.fill();

    // Stars
    const starSeed = [0.12,0.34,0.56,0.78,0.23,0.67,0.45,0.89,0.11,0.55,0.33,0.77,0.19,0.63,0.41,0.85,0.07,0.51,0.29,0.73];
    starSeed.forEach((s,i) => {
        const sx = CW * s, sy = CH * 0.5 * starSeed[(i+7)%20];
        const sr = 0.5 + s*1.2;
        const blink = 0.3 + 0.7*Math.abs(Math.sin(t*0.001 + i));
        ctx2.globalAlpha = blink * 0.7;
        ctx2.fillStyle = '#fff';
        ctx2.beginPath(); ctx2.arc(sx, sy, sr, 0, Math.PI*2); ctx2.fill();
    });
    ctx2.globalAlpha = 1;

    // City silhouette
    const cityY = CH * 0.54;
    const buildings = [
        {x:0,w:80,h:120},{x:85,w:50,h:80},{x:138,w:100,h:160},
        {x:240,w:60,h:100},{x:303,w:120,h:190},{x:425,w:70,h:110},
        {x:498,w:90,h:145},{x:590,w:55,h:85},{x:648,w:130,h:175},
        {x:780,w:65,h:105},{x:848,w:100,h:155},{x:950,w:80,h:125},
        {x:1033,w:110,h:165},{x:1145,w:70,h:95},{x:1218,w:90,h:140},
        {x:1310,w:120,h:180},{x:1432,w:60,h:100},{x:1494,w:80,h:130}
    ];
    buildings.forEach(b => {
        ctx2.fillStyle = '#050A12';
        ctx2.fillRect(b.x, cityY - b.h, b.w, b.h);
        // Roof edge
        ctx2.strokeStyle = 'rgba(0,240,255,0.08)'; ctx2.lineWidth = 1;
        ctx2.beginPath(); ctx2.moveTo(b.x,cityY-b.h); ctx2.lineTo(b.x+b.w,cityY-b.h); ctx2.stroke();
        // Windows
        for (let wr = 0; wr < Math.floor(b.h/18); wr++) {
            for (let wc = 0; wc < Math.floor(b.w/12); wc++) {
                if (Math.abs(Math.sin(b.x*0.1+wr*3.7+wc*7.3)) > 0.45) {
                    const wa = 0.3 + 0.5*Math.abs(Math.sin(t*0.0004+b.x+wr+wc));
                    ctx2.globalAlpha = wa;
                    ctx2.fillStyle = '#FFB700';
                    ctx2.fillRect(b.x + wc*12 + 4, cityY - b.h + wr*18 + 5, 5, 7);
                    ctx2.globalAlpha = 1;
                }
            }
        }
    });

    // Road ground
    const roadY = CH * 0.54;
    const roadGrad = ctx2.createLinearGradient(0, roadY, 0, CH);
    roadGrad.addColorStop(0, '#0C1018');
    roadGrad.addColorStop(0.4, '#12161E');
    roadGrad.addColorStop(1, '#080C12');
    ctx2.fillStyle = roadGrad;
    ctx2.fillRect(0, roadY, CW, CH - roadY);

    // Road edge line
    ctx2.strokeStyle = 'rgba(255,183,0,0.25)'; ctx2.lineWidth = 2;
    ctx2.beginPath(); ctx2.moveTo(0, roadY+1); ctx2.lineTo(CW, roadY+1); ctx2.stroke();

    // Dashed centre line (animated)
    const dashOffset = (t * 0.18) % 100;
    ctx2.strokeStyle = 'rgba(255,255,255,0.12)'; ctx2.lineWidth = 2;
    ctx2.setLineDash([60, 40]); ctx2.lineDashOffset = -dashOffset;
    ctx2.beginPath(); ctx2.moveTo(0, roadY + (CH-roadY)*0.45); ctx2.lineTo(CW, roadY + (CH-roadY)*0.45); ctx2.stroke();
    ctx2.setLineDash([]);

    // Kerb bottom
    ctx2.strokeStyle = 'rgba(255,255,255,0.05)'; ctx2.lineWidth = 3;
    ctx2.beginPath(); ctx2.moveTo(0, CH-4); ctx2.lineTo(CW, CH-4); ctx2.stroke();

    ctx2.restore();
}

/* ── Shockwave rings ────────────────────────────────────── */
let shockwaves = [];
function spawnShockwave(cx, cy) {
    shockwaves.push({ x:cx, y:cy, r:10, alpha:1 });
}
function updateShockwaves() {
    shockwaves = shockwaves.filter(s => s.alpha > 0.01);
    shockwaves.forEach(s => { s.r += 12; s.alpha -= 0.025; });
}
function drawShockwaves() {
    shockwaves.forEach(s => {
        ctx2.save(); ctx2.globalAlpha = s.alpha;
        ctx2.strokeStyle = '#FFB700'; ctx2.lineWidth = 2;
        ctx2.beginPath(); ctx2.arc(s.x, s.y, s.r, 0, Math.PI*2); ctx2.stroke();
        ctx2.globalAlpha = s.alpha * 0.3;
        ctx2.strokeStyle = '#FF3B6B'; ctx2.lineWidth = 4;
        ctx2.beginPath(); ctx2.arc(s.x, s.y, s.r*0.7, 0, Math.PI*2); ctx2.stroke();
        ctx2.restore();
    });
}

/* ── Explosion flash ────────────────────────────────────── */
let flashAlpha = 0;
function triggerFlash() { flashAlpha = 1; }
function drawFlash() {
    if (flashAlpha <= 0) return;
    ctx2.save(); ctx2.globalAlpha = flashAlpha;
    ctx2.fillStyle = '#FFFFFF';
    ctx2.fillRect(0, 0, CW, CH);
    ctx2.restore();
    flashAlpha = Math.max(0, flashAlpha - 0.06);
}

/* ── Emergency light overlay ────────────────────────────── */
let emergencyAlpha = 0, emergencyPhase = 0;
function drawEmergencyOverlay(t) {
    if (emergencyAlpha <= 0) return;
    emergencyPhase = t * 0.006;
    const col = Math.sin(emergencyPhase) > 0 ? 'rgba(255,59,107,' : 'rgba(0,80,255,';
    ctx2.save(); ctx2.globalAlpha = emergencyAlpha * 0.12;
    ctx2.fillStyle = col + '1)';
    ctx2.fillRect(0, 0, CW, CH);
    ctx2.restore();
}

/* ── AR Grid overlay ────────────────────────────────────── */
function drawARGrid(alpha) {
    if (alpha <= 0) return;
    ctx2.save(); ctx2.globalAlpha = alpha;
    ctx2.strokeStyle = 'rgba(0,240,255,0.15)'; ctx2.lineWidth = 0.5;
    const gs = 60;
    for (let x = 0; x < CW; x+=gs) { ctx2.beginPath(); ctx2.moveTo(x,0); ctx2.lineTo(x,CH); ctx2.stroke(); }
    for (let y = 0; y < CH; y+=gs) { ctx2.beginPath(); ctx2.moveTo(0,y); ctx2.lineTo(CW,y); ctx2.stroke(); }
    ctx2.restore();
}

/* ── Phase orchestration ────────────────────────────────── */
/*
  Phases:
  0. 'approach'   0–3500ms   Cars move toward each other
  1. 'impact'     3500ms     Explosion, flash, shake
  2. 'aftermath'  3500–8000  Fire, smoke, person on ground
  3. 'emergency'  6000–12000 Ambulance arrives, lights
  4. 'reveal'     10000+     HUD text, narration appear
*/

// Vehicle state (x positions, angles, crumple)
let mainCar    = { x: 0, y: 0, angle: 0, crumple: 0 };
let truck      = { x: 0, y: 0, angle: 0, crumple: 0 };
let person     = { x: 0, y: 0, vx: 0, vy: 0, angle: 0, alpha: 0, active: false };
let ambul      = { x: -200, y: 0, active: false, lightPhase: 0 };
let impactDone = false;
let totalT     = 0;
let shakeStr   = 0;
let arGridAlpha = 0.6;

function crashLoop(ts) {
    if (!crashLastTs) crashLastTs = ts;
    const dt = Math.min(ts - crashLastTs, 50);
    crashLastTs = ts;
    totalT += dt;

    // ── Layout constants (responsive)
    const roadY     = CH * 0.54;
    const laneY     = roadY + (CH - roadY) * 0.28;   // upper lane (main car)
    const laneY2    = roadY + (CH - roadY) * 0.55;   // lower lane (truck)
    const carW      = CW * 0.14, carH  = carW * 0.48;
    const truckW    = CW * 0.22, truckH = truckW * 0.44;
    const ambW      = CW * 0.14, ambH  = ambW * 0.44;
    const crashX    = CW * 0.48;

    // Phase transitions
    if (totalT < 3600) crashPhase = 'approach';
    else if (totalT < 4200) crashPhase = 'impact';
    else if (totalT < 9500) crashPhase = 'aftermath';
    else if (totalT < 15000) crashPhase = 'emergency';
    else crashPhase = 'reveal';

    // ── Vehicle positions
    if (crashPhase === 'approach') {
        const p = Math.min(totalT / 3600, 1);
        const ease = p < 0.8 ? p / 0.8 : 1;               // accelerate then hold
        mainCar.x = -carW + (crashX - carW/2 + carW) * ease; // left → crash
        mainCar.y = laneY;

        // Truck approaches faster from right
        const tp = Math.min(totalT / 3000, 1);
        const tease = tp < 0.85 ? tp / 0.85 : 1;
        truck.x = CW + 40 - (CW - crashX + truckW*0.5) * tease;
        truck.y = laneY2;

        arGridAlpha = 0.6 - p * 0.3;

        // Speedometer pulses
        if (Math.floor(totalT/60) % 2 === 0) {
            const sv = document.getElementById('speed-val');
            if (sv) sv.textContent = (80 + Math.floor(Math.random()*10)) + ' <span>km/h</span>';
        }
    }

    if (crashPhase === 'impact' && !impactDone) {
        impactDone = true;
        // Trigger everything at once
        triggerFlash();
        for (let i=0;i<5;i++) spawnShockwave(crashX, laneY2 + carH*0.5);
        spawnSparks(crashX, laneY2+carH*0.4, 150);
        spawnDebris(crashX, laneY2+carH*0.3, 60);
        shakeStr = 1;

        // Person ejected
        person = {
            x: crashX, y: laneY + carH*0.3,
            vx: 5 + Math.random()*4,
            vy: -(8 + Math.random()*4),
            angle: 0, alpha: 1, active: true
        };

        // Show impact HUD
        const hi = document.getElementById('hud-impact');
        if (hi) { hi.style.transition='opacity 0.3s'; hi.style.opacity='1'; }

        // Change speedometer to 0
        const sv = document.getElementById('speed-val');
        if (sv) sv.innerHTML = '0 <span>km/h</span>';
    }

    if (crashPhase === 'impact') {
        // Freeze vehicles at crash point
        mainCar.x = crashX - carW * 0.6;
        mainCar.y = laneY;
        mainCar.crumple = Math.min((totalT - 3600) / 300, 1);
        mainCar.angle   = Math.sin(totalT * 0.08) * 0.04 * Math.max(0, 1 - (totalT-3600)/400);

        truck.x = crashX;
        truck.y = laneY2;
        truck.crumple = Math.min((totalT-3600)/400, 0.8);
        shakeStr = Math.max(0, 1 - (totalT-3600)/600);
    }

    if (crashPhase === 'aftermath') {
        const ap = (totalT - 4200) / 5300;
        // Vehicles stay crumpled
        mainCar.x = crashX - carW * 0.65;
        mainCar.y = laneY;
        mainCar.angle = 0.12;
        mainCar.crumple = 1;
        truck.x = crashX - truckW * 0.08;
        truck.y = laneY2;
        truck.crumple = 0.8;
        truck.angle = -0.06;

        // Fire and smoke at crash point
        if (totalT % 60 < dt + 5) {
            spawnFire(crashX + carW*0.1, laneY + carH*0.2);
            spawnSmoke(crashX + carW*0.05, laneY + carH*0.15);
        }

        arGridAlpha = Math.max(0, 0.3 - ap * 0.3);
        emergencyAlpha = Math.min(ap * 2, 0.8);
    }

    if (crashPhase === 'emergency') {
        mainCar.angle = 0.12; mainCar.crumple = 1;
        truck.crumple = 0.8; truck.angle = -0.06;

        // Ambulance drives in
        if (!ambul.active) { ambul.active = true; ambulanceX = -ambW - 20; }
        const ambTarget = crashX - carW * 3;
        ambulanceX += (ambTarget - ambulanceX) * 0.015;
        ambul.x = ambulanceX;
        ambul.y = laneY - ambH - 10;
        ambul.lightPhase = totalT / 1000;

        // Sparse fire/smoke
        if (totalT % 80 < dt + 8) {
            spawnFire(crashX + carW*0.1, laneY + carH*0.2);
            spawnSmoke(crashX + carW*0.05, laneY + carH*0.1);
        }

        emergencyAlpha = 0.8;

        // SOS HUD
        const hs = document.getElementById('hud-sos');
        if (hs && hs.style.opacity !== '1') { hs.style.transition='opacity 0.5s'; hs.style.opacity='1'; }
    }

    if (crashPhase === 'reveal' && !crashComplete) {
        crashComplete = true;
        emergencyAlpha = 0.6;

        // Show title + stats + narration
        const tw = document.getElementById('crash-title-wrap');
        const sr = document.getElementById('crash-stats-row');
        const nr = document.getElementById('crash-narration');
        if (tw) { tw.style.transition='opacity 0.8s'; tw.style.opacity='1'; }
        setTimeout(() => { if(sr) { sr.style.transition='opacity 0.8s'; sr.style.opacity='1'; } }, 600);
        setTimeout(() => { if(nr) { nr.style.transition='opacity 0.8s'; nr.style.opacity='1'; } }, 1200);
    }

    // Update person physics
    if (person.active) {
        person.vy += 0.35;                             // gravity
        person.x  += person.vx;
        person.y  += person.vy;
        person.angle += person.vx * 0.04;
        person.vx  *= 0.98;
        if (person.y > laneY + carH * 0.8) {          // landed
            person.y  = laneY + carH * 0.8;
            person.vy = 0; person.vx = 0;
            person.angle = 1.4;                        // slumped
        }
    }

    // Decay shake
    shakeStr *= 0.88;

    // ── DRAW ──────────────────────────────────────────────
    ctx2.clearRect(0, 0, CW, CH);

    drawEnvironment(totalT, shakeStr);
    drawRain();
    drawARGrid(arGridAlpha);
    drawEmergencyOverlay(totalT);

    // Vehicles
    if (crashPhase !== 'reveal' || !crashComplete || totalT < 16000) {
        // Main car
        ctx2.save();
        ctx2.translate(mainCar.x + carW/2, mainCar.y + carH/2);
        ctx2.rotate(mainCar.angle);
        ctx2.translate(-(mainCar.x + carW/2), -(mainCar.y + carH/2));
        drawCar(mainCar.x, mainCar.y, carW, carH, '#1A2E4A', '#00F0FF', 1, mainCar.crumple, crashPhase === 'approach');
        ctx2.restore();

        // Truck
        ctx2.save();
        ctx2.translate(truck.x + truckW/2, truck.y + truckH/2);
        ctx2.rotate(truck.angle || 0);
        ctx2.translate(-(truck.x + truckW/2), -(truck.y + truckH/2));
        drawTruck(truck.x, truck.y, truckW, truckH, truck.crumple || 0);
        ctx2.restore();
    }

    // Ambulance
    if (ambul.active) {
        drawAmbulance(ambul.x, ambul.y, ambW, ambH, ambul.lightPhase);
    }

    // Person
    if (person.active) {
        drawPerson(person.x, person.y, person.angle, person.alpha);
    }

    // Particles & effects
    updateParticles();
    updateShockwaves();
    drawParticles();
    drawShockwaves();
    drawFlash();

    // AR speed HUD bracket corners on canvas
    if (crashPhase === 'approach') {
        ctx2.save(); ctx2.strokeStyle='rgba(0,240,255,0.3)'; ctx2.lineWidth=1.5;
        // Bottom AR corners
        const bcy = CH - 80, bcx1 = 16, bcx2 = CW - 16, bcS = 28;
        [[bcx1,bcy,1,1],[bcx2-bcS,bcy,1,1],[bcx1,bcy+bcS,-1,1],[bcx2-bcS,bcy+bcS,-1,1]].forEach(([x,y,sx,sy]) => {
            ctx2.save(); ctx2.translate(x,y); ctx2.scale(sx<0?-1:1,sy<0?-1:1);
            ctx2.beginPath(); ctx2.moveTo(0,0); ctx2.lineTo(bcS,0); ctx2.moveTo(0,0); ctx2.lineTo(0,bcS); ctx2.stroke();
            ctx2.restore();
        });
        ctx2.restore();
    }

    crashRAF = requestAnimationFrame(crashLoop);
}