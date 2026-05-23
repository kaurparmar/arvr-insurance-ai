
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Risk Node Core — {{ config('app.name', 'LifeShield XR') }}</title>
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
        body { font-family: 'DM Sans', sans-serif; transition: background .3s, color .3s; }
        .dark body { background: var(--bg-void); color: #EEF2FF; }
        body:not(.dark-mode), html:not(.dark) body { background: #F0F4FF; color: #0F172A; }
        
        .vr-scanlines {
            display: none; position: fixed; inset: 0; pointer-events: none; z-index: 1;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,.025) 2px, rgba(0,0,0,.025) 4px);
        }
        .dark .vr-scanlines { display: block; }
        #admin-particles { position: fixed; inset: 0; pointer-events: none; z-index: 0; opacity: 0; transition: opacity .5s; }
        .dark #admin-particles { opacity: 1; }

        .xr-chip { display: inline-flex; align-items: center; gap: 8px; border-radius: 100px; padding: 5px 16px; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
        .chip-cyan { background: rgba(0,240,255,.1); border: 1px solid rgba(0,240,255,.25); color: var(--cyan); }
        .chip-dot { width: 6px; height: 6px; background: var(--cyan); border-radius: 50%; animation: blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        .xr-card { border-radius: 24px; transition: transform .3s, border-color .3s, box-shadow .3s; position: relative; overflow: hidden; }
        html:not(.dark) .xr-card { background: #fff; border: 1px solid rgba(0,0,0,.07); box-shadow: 0 4px 24px rgba(0,0,0,.06); }
        .dark .xr-card { background: var(--bg-panel); border: 1px solid var(--border-w); }
        .xr-card:hover { transform: translateY(-4px); border-color: rgba(0,240,255,.3); }
        .xr-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--cyan), transparent); opacity: 0; transition: opacity .3s; }
        .xr-card:hover::before { opacity: 1; }

        .grad-border { padding: 1px; background: linear-gradient(135deg, rgba(0,240,255,.4), rgba(139,92,246,.4), rgba(255,59,107,.3)); border-radius: 24px; }
        .grad-border-inner { border-radius: 23px; padding: 40px; height: 100%; }
        html:not(.dark) .grad-border-inner { background: #fff; }
        .dark .grad-border-inner { background: #07111F; }
        
        .syne { font-family: 'Syne', sans-serif; }
        .grad-text { background: linear-gradient(135deg, var(--cyan) 0%, var(--violet) 50%, var(--rose) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; background-size: 200%; }
        .narration-box { border-radius: 14px; padding: 18px 22px; border-left: 3px solid var(--cyan); font-style: italic; font-size: 14px; line-height: 1.6; }
        html:not(.dark) .narration-box { background: #F0F7FF; color: #475569; }
        .dark .narration-box { background: rgba(255,255,255,.03); color: #8892AA; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#03060F] text-slate-900 dark:text-[#EEF2FF]">

    <div class="vr-scanlines"></div>
    <canvas id="admin-particles"></canvas>

    <x-navbar :is-authenticated="auth()->check()" />

    <main class="relative z-10" style="padding-top:100px; min-h: 85vh;">
        @yield('content')
    </main>

    <footer style="text-align:center;padding:28px;font-size:13px;border-top:1px solid" class="text-slate-400 dark:text-[#8892AA] dark:border-white/5 border-slate-200 mt-20">
        <span>© {{ date('Y') }} LifeShield XR Terminal. All rights reserved.</span>
    </footer>

    <script>
    (function(){
        const canvas=document.getElementById('admin-particles');
        const ctx=canvas.getContext('2d');
        let W,H;
        const isDark=()=>document.documentElement.classList.contains('dark');
        const resize=()=>{W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight;};
        window.addEventListener('resize',resize);resize();
        class P{constructor(){this.reset();}reset(){this.x=Math.random()*W;this.y=Math.random()*H;this.r=Math.random()*1.4+.3;this.vx=(Math.random()-.5)*.2;this.vy=(Math.random()-.5)*.2;this.a=Math.random()*.35+.08;this.c=Math.random()>.6?'#00F0FF':Math.random()>.5?'#8B5CF6':'#ffffff';}update(){this.x+=this.vx;this.y+=this.vy;if(this.x<0||this.x>W||this.y<0||this.y>H)this.reset();}draw(){ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);ctx.fillStyle=this.c;ctx.globalAlpha=this.a;ctx.fill();}}
        const ps=Array.from({length:50},()=>new P());
        const loop=()=>{if(!isDark()){ctx.clearRect(0,0,W,H);requestAnimationFrame(loop);return;}ctx.clearRect(0,0,W,H);ctx.globalAlpha=1;for(let i=0;i<ps.length;i++)for(let j=i+1;j<ps.length;j++){const dx=ps[i].x-ps[j].x,dy=ps[i].y-ps[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<90){ctx.beginPath();ctx.moveTo(ps[i].x,ps[i].y);ctx.lineTo(ps[j].x,ps[j].y);ctx.strokeStyle='rgba(0,240,255,'+(0.03*(1-d/90))+')';ctx.lineWidth=.5;ctx.stroke();}}ps.forEach(p=>{p.update();p.draw();});requestAnimationFrame(loop);};loop();
    })();
    </script>
</body>
</html>