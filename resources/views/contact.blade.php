<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Contact Us - {{ config('app.name', 'LifeShield XR') }}</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        (function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
     <style>
        :root {
            --cyan:#00F0FF; --violet:#8B5CF6; --rose:#FF3B6B; --emerald:#00E676; --amber:#FFB700;
            --bg-void:#03060F; --bg-deep:#060C1A; --bg-panel:rgba(8,14,30,.92);
            --text-mid:#8892AA; --border:rgba(0,240,255,.1); --border-w:rgba(255,255,255,.07);
        }
        /* Light mode overrides */
        .light-bg   { background: #F8FAFF; }
        .light-card { background: #FFFFFF; border-color: rgba(0,0,0,.08); }
        .light-text { color: #0F172A; }
        .light-sub  { color: #475569; }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            transition: background .3s, color .3s;
        }

        /* ── Dark mode body ── */
        body.dark-mode, .dark body {
            background: var(--bg-void);
            color: #EEF2FF;
        }
        /* ── Light mode body ── */
        body:not(.dark-mode), html:not(.dark) body {
            background: #F0F4FF;
            color: #0F172A;
        }

        /* Tailwind dark: prefix targets */
        .dark body { background: var(--bg-void); color: #EEF2FF; }

        /* Scanlines only dark */
        .vr-scanlines {
            display: none;
            position: fixed; inset: 0; pointer-events: none; z-index: 1;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,.025) 2px, rgba(0,0,0,.025) 4px);
        }
        .dark .vr-scanlines { display: block; }

        /* ── Particle canvas ── */
        #about-particles {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            opacity: 0; transition: opacity .5s;
        }
        .dark #about-particles { opacity: 1; }

        /* ── Glows ── */
        .glow-cyan   { position: absolute; border-radius: 50%; filter: blur(100px); background: rgba(0,240,255,.07); pointer-events: none; }
        .glow-violet { position: absolute; border-radius: 50%; filter: blur(120px); background: rgba(139,92,246,.06); pointer-events: none; }
        .glow-rose   { position: absolute; border-radius: 50%; filter: blur(100px); background: rgba(255,59,107,.05); pointer-events: none; }
        .dark .glow-cyan   { background: rgba(0,240,255,.09); }
        .dark .glow-violet { background: rgba(139,92,246,.08); }
        .dark .glow-rose   { background: rgba(255,59,107,.07); }

        /* ── Chip badge ── */
        .xr-chip {
            display: inline-flex; align-items: center; gap: 8px;
            border-radius: 100px; padding: 5px 16px;
            font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        }
        .chip-cyan {
            background: rgba(0,240,255,.1); border: 1px solid rgba(0,240,255,.25); color: var(--cyan);
        }
        .dark .chip-cyan { background: rgba(0,240,255,.08); }

        .chip-dot { width: 6px; height: 6px; background: var(--cyan); border-radius: 50%; animation: blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ── Cards ── */
        .xr-card {
            border-radius: 24px;
            transition: transform .3s, border-color .3s, box-shadow .3s;
            position: relative; overflow: hidden;
        }
        /* Light card */
        html:not(.dark) .xr-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }
        /* Dark card */
        .dark .xr-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-w);
        }
        .xr-card:hover {
            transform: translateY(-6px);
            border-color: rgba(0,240,255,.3);
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .xr-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0; transition: opacity .3s;
        }
        .xr-card:hover::before { opacity: 1; }

        /* ── Gradient card border ── */
        .grad-border {
            padding: 1px;
            background: linear-gradient(135deg, rgba(0,240,255,.4), rgba(139,92,246,.4), rgba(255,59,107,.3));
            border-radius: 24px;
        }
        .grad-border-inner {
            border-radius: 23px;
            padding: 40px;
            height: 100%;
        }
        html:not(.dark) .grad-border-inner { background: #fff; }
        .dark .grad-border-inner { background: #07111F; }

        /* ── Syne headings ── */
        .syne { font-family: 'Syne', sans-serif; }

        /* ── Gradient text ── */
        .grad-text {
            background: linear-gradient(135deg, var(--cyan) 0%, var(--violet) 50%, var(--rose) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; background-size: 200%;
            animation: textShine 5s linear infinite;
        }
        @keyframes textShine { to { background-position: 200% center; } }

        .grad-text-cv {
            background: linear-gradient(135deg, var(--cyan), var(--violet));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .grad-text-vr {
            background: linear-gradient(135deg, var(--violet), var(--rose));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .grad-text-eg {
            background: linear-gradient(135deg, var(--emerald), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ── Narration box ── */
        .narration-box {
            border-radius: 14px; padding: 20px 24px;
            border-left: 3px solid var(--cyan);
            font-style: italic; font-size: 15px; line-height: 1.7;
        }
        html:not(.dark) .narration-box { background: #F0F7FF; color: #475569; border-color: var(--cyan); }
        .dark .narration-box { background: rgba(255,255,255,.03); color: var(--text-mid); }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 20px; padding: 28px 20px; text-align: center;
            transition: transform .3s;
        }
        html:not(.dark) .stat-card { background: #fff; border: 1px solid rgba(0,0,0,.06); box-shadow: 0 2px 16px rgba(0,0,0,.05); }
        .dark .stat-card { background: var(--bg-panel); border: 1px solid var(--border-w); }
        .stat-card:hover { transform: translateY(-4px); }

        /* ── Icon circle ── */
        .icon-circle {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 20px;
        }

        /* ── Vision list ── */
        .vision-item {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid;
            transition: color .2s;
        }
        html:not(.dark) .vision-item { border-color: rgba(0,0,0,.06); color: #475569; }
        .dark .vision-item { border-color: var(--border-w); color: var(--text-mid); }
        .vision-item:last-child { border-bottom: none; }
        .vision-item:hover { color: #0F172A; }
        .dark .vision-item:hover { color: #EEF2FF; }
        .vision-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--cyan); margin-top: 6px; flex-shrink: 0; transition: transform .2s; }
        .vision-item:hover .vision-dot { transform: scale(1.5); }

        /* ── Timeline ── */
        .timeline-item {
            display: flex; gap: 24px; padding-bottom: 40px;
            position: relative;
        }
        .timeline-item::before {
            content: ''; position: absolute; left: 19px; top: 40px; bottom: 0; width: 1px;
        }
        html:not(.dark) .timeline-item::before { background: rgba(0,0,0,.08); }
        .dark .timeline-item::before { background: var(--border-w); }
        .timeline-item:last-child::before { display: none; }
        .t-dot {
            width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 16px; flex-shrink: 0; border: 2px solid;
        }
        html:not(.dark) .t-dot { background: #F0F4FF; border-color: rgba(0,0,0,.1); }
        .dark .t-dot { background: var(--bg-deep); border-color: var(--border); }
        .t-dot.cyan-dot { border-color: var(--cyan); }
        .t-dot.violet-dot { border-color: var(--violet); }
        .t-dot.rose-dot { border-color: var(--rose); }

        /* ── CTA section ── */
        .cta-wrap {
            border-radius: 40px; padding: 1px;
            background: linear-gradient(135deg, rgba(0,240,255,.3), rgba(139,92,246,.3), rgba(255,59,107,.2));
        }
        .cta-inner {
            border-radius: 39px; padding: 72px 40px; text-align: center;
        }
        html:not(.dark) .cta-inner { background: linear-gradient(135deg, #EEF4FF, #F5EEFF); }
        .dark .cta-inner { background: linear-gradient(135deg, #040C18, #080512); }

        /* ── Buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--cyan); color: #020F14;
            font-family: 'Syne', sans-serif; font-weight: 700;
            padding: 14px 32px; border-radius: 100px; border: none; cursor: pointer;
            font-size: 15px; transition: all .2s; text-decoration: none;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,240,255,.3); }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: #0F172A;
            font-family: 'DM Sans', sans-serif; font-weight: 500;
            padding: 14px 32px; border-radius: 100px; cursor: pointer;
            font-size: 15px; transition: all .2s; text-decoration: none;
            border: 1px solid rgba(0,0,0,.15);
        }
        .dark .btn-outline { color: #EEF2FF; border-color: rgba(255,255,255,.12); }
        .btn-outline:hover { border-color: var(--cyan); color: var(--cyan); transform: translateY(-2px); }

        /* ── Team card ── */
        .team-card {
            border-radius: 24px; padding: 32px 24px; text-align: center;
            transition: transform .3s, box-shadow .3s;
        }
        html:not(.dark) .team-card { background: #fff; border: 1px solid rgba(0,0,0,.07); box-shadow: 0 4px 20px rgba(0,0,0,.06); }
        .dark .team-card { background: var(--bg-panel); border: 1px solid var(--border-w); }
        .team-card:hover { transform: translateY(-8px); box-shadow: 0 24px 60px rgba(0,0,0,.2); }

        .team-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 700;
        }

        /* ── Section layouts ── */
        .section { max-width: 1100px; margin: 0 auto; padding: 80px 32px; }
        .section-sm { max-width: 1100px; margin: 0 auto; padding: 0 32px 80px; }

        /* ── Scroll indicator ── */
        @keyframes bounce { 0%,100%{transform:translateY(0)}50%{transform:translateY(8px)} }
        .scroll-dot { animation: bounce 2s infinite; }

        /* ── Fade up animations ── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { opacity: 0; animation: fadeUp .6s forwards; }
        .fade-up-1 { animation-delay: .1s; }
        .fade-up-2 { animation-delay: .25s; }
        .fade-up-3 { animation-delay: .4s; }
        .fade-up-4 { animation-delay: .55s; }

        /* ── Muted text ── */
        .text-muted { color: #475569; }
        .dark .text-muted { color: var(--text-mid); }
        .text-hi { color: #0F172A; }
        .dark .text-hi { color: #EEF2FF; }
        .text-sub { color: #64748B; }
        .dark .text-sub { color: #8892AA; }

        @media(max-width:768px){
            .grid-2,.grid-3,.grid-4,.team-grid,.stat-grid { grid-template-columns: 1fr !important; }
            .section { padding: 60px 20px; }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#03060F] text-slate-900 dark:text-[#EEF2FF] transition-colors duration-300">
    <!-- Visual Noise Elements -->
    <div class="vr-scanlines"></div>
    <canvas id="about-particles"></canvas>

    <x-navbar :is-authenticated="auth()->check()" />

    <main class="relative overflow-hidden" style="padding-top:60px">
        <!-- Background Glows -->
        <div class="glow-cyan" style="width:600px;height:600px;top:-200px;left:-150px;opacity:.6"></div>
        <div class="glow-violet" style="width:700px;height:700px;top:200px;right:-200px;opacity:.5"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-16 fade-up fade-up-1">
                <span class="xr-chip chip-cyan mb-4">
                    <span class="chip-dot"></span> Support Hub
                </span>
                <h1 class="syne text-5xl md:text-7xl font-extrabold text-hi mb-6 tracking-tighter">
                    Get in <span class="grad-text">Touch</span>
                </h1>
                <p class="text-xl text-sub max-w-2xl mx-auto">
                    We are bridging the gap between digital support and human connection.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-start">
                <!-- Left Column: Contact Info -->
                <div class="fade-up fade-up-2">
                    <h2 class="syne text-2xl font-bold text-hi mb-8">Contact Information</h2>
                    
                    <div class="space-y-8">
                        <!-- Address -->
                        <div class="vision-item border-none py-0">
                            <div class="icon-circle !mb-0 !w-12 !h-12" style="background:rgba(0,240,255,.1)">📍</div>
                            <div>
                                <h3 class="font-bold text-hi">Office</h3>
                                <p class="text-sub">Mumbai, Digital Plaza, India</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="vision-item border-none py-0">
                            <div class="icon-circle !mb-0 !w-12 !h-12" style="background:rgba(139,92,246,.1)">✉️</div>
                            <div>
                                <h3 class="font-bold text-hi">Email</h3>
                                <p class="text-sub">support@lifeshield-xr.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card implemented as xr-card -->
                    <div class="xr-card mt-12 p-8">
                        <h3 class="syne text-lg font-semibold text-hi mb-4">Network Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="stat-card !p-4 !shadow-none">
                                <p class="syne text-2xl font-bold" style="color:var(--cyan)">{{ number_format($planCount) }}</p>
                                <p class="text-[10px] uppercase tracking-widest text-sub">Active Plans</p>
                            </div>
                            <div class="stat-card !p-4 !shadow-none">
                                <p class="syne text-2xl font-bold" style="color:var(--violet)">{{ number_format($activeUsers) }}</p>
                                <p class="text-[10px] uppercase tracking-widest text-sub">Members</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="fade-up fade-up-3">
                    <div class="grad-border">
                        <div class="grad-border-inner !p-8">
                            <form action="#" class="space-y-5">
                                <div>
                                    <label class="block text-hi font-bold mb-2 text-sm uppercase tracking-wider">Name</label>
                                    <input type="text" class="w-full px-4 py-3 xr-card !bg-transparent border-slate-300 dark:border-white/10 text-hi focus:ring-2 focus:ring-cyan-500 outline-none transition-all" placeholder="Your Name">
                                </div>
                                <div>
                                    <label class="block text-hi font-bold mb-2 text-sm uppercase tracking-wider">Email</label>
                                    <input type="email" class="w-full px-4 py-3 xr-card !bg-transparent border-slate-300 dark:border-white/10 text-hi focus:ring-2 focus:ring-cyan-500 outline-none transition-all" placeholder="your@email.com">
                                </div>
                                <div>
                                    <label class="block text-hi font-bold mb-2 text-sm uppercase tracking-wider">Message</label>
                                    <textarea rows="4" class="w-full px-4 py-3 xr-card !bg-transparent border-slate-300 dark:border-white/10 text-hi focus:ring-2 focus:ring-cyan-500 outline-none transition-all" placeholder="How can we help?"></textarea>
                                </div>
                                <button type="submit" class="btn-primary w-full justify-center py-4">
                                    Initialize Transmission →
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
     <footer style="text-align:center;padding:28px;font-size:13px;border-top:1px solid" class="text-sub" style="border-color:rgba(255,255,255,.06)">
        <span class="dark:border-white/5 border-slate-200">© {{ date('Y') }} LifeShield XR. All rights reserved.</span>
    </footer>

    <script>
    (function(){
        const canvas=document.getElementById('about-particles');
        const ctx=canvas.getContext('2d');
        let W,H;
        const isDark=()=>document.documentElement.classList.contains('dark');
        const resize=()=>{W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight;};
        window.addEventListener('resize',resize);resize();
        class P{constructor(){this.reset();}reset(){this.x=Math.random()*W;this.y=Math.random()*H;this.r=Math.random()*1.4+.3;this.vx=(Math.random()-.5)*.2;this.vy=(Math.random()-.5)*.2;this.a=Math.random()*.35+.08;this.c=Math.random()>.6?'#00F0FF':Math.random()>.5?'#8B5CF6':'#ffffff';}update(){this.x+=this.vx;this.y+=this.vy;if(this.x<0||this.x>W||this.y<0||this.y>H)this.reset();}draw(){ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);ctx.fillStyle=this.c;ctx.globalAlpha=this.a;ctx.fill();}}
        const ps=Array.from({length:70},()=>new P());
        const loop=()=>{if(!isDark()){ctx.clearRect(0,0,W,H);requestAnimationFrame(loop);return;}ctx.clearRect(0,0,W,H);ctx.globalAlpha=1;for(let i=0;i<ps.length;i++)for(let j=i+1;j<ps.length;j++){const dx=ps[i].x-ps[j].x,dy=ps[i].y-ps[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<90){ctx.beginPath();ctx.moveTo(ps[i].x,ps[i].y);ctx.lineTo(ps[j].x,ps[j].y);ctx.strokeStyle='rgba(0,240,255,'+(0.03*(1-d/90))+')';ctx.lineWidth=.5;ctx.stroke();}}ps.forEach(p=>{p.update();p.draw();});requestAnimationFrame(loop);};loop();
    })();
    </script>

</body>
