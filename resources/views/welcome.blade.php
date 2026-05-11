<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name','LifeShield XR') }} — AR/VR Life Insurance</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>(function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();</script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        :root{--cyan:#00F0FF;--violet:#8B5CF6;--rose:#FF3B6B;--emerald:#00E676;--amber:#FFB700;--bg-void:#03060F;--bg-panel:rgba(8,14,30,.92);--text-mid:#8892AA;--border-w:rgba(255,255,255,.07);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;overflow-x:hidden;transition:background .3s;}
        html:not(.dark) body{background:#F0F4FF;color:#0F172A;}
        .dark body{background:var(--bg-void);color:#EEF2FF;}
        .syne{font-family:'Syne',sans-serif;}
        .vr-scanlines{display:none;position:fixed;inset:0;pointer-events:none;z-index:1;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.025) 2px,rgba(0,0,0,.025) 4px);}
        .dark .vr-scanlines{display:block;}
        canvas#wc{position:fixed;inset:0;pointer-events:none;z-index:0;opacity:0;}
        .dark canvas#wc{opacity:1;}
        .glow{position:absolute;border-radius:50%;filter:blur(120px);pointer-events:none;}
        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}
        /* Hero */
        .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;position:relative;overflow:hidden;padding:80px 24px 60px;}
        .xr-chip{display:inline-flex;align-items:center;gap:8px;border-radius:100px;padding:5px 16px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;}
        .chip-live{background:rgba(0,240,255,.1);border:1px solid rgba(0,240,255,.25);color:var(--cyan);}
        .chip-dot{width:7px;height:7px;background:var(--cyan);border-radius:50%;animation:blink 1.5s infinite;}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
        /* Gradient text */
        .grad-cv{background:linear-gradient(135deg,var(--cyan),var(--violet));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
        .grad-all{background:linear-gradient(135deg,var(--cyan),var(--violet),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;background-size:200%;animation:shine 5s linear infinite;}
        @keyframes shine{to{background-position:200% center}}
        /* Buttons */
        .btn-hero-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--cyan),var(--violet));color:#fff;padding:16px 36px;border-radius:100px;font-family:'Syne',sans-serif;font-weight:700;font-size:15px;text-decoration:none;transition:all .2s;border:none;cursor:pointer;}
        .btn-hero-primary:hover{transform:translateY(-3px);box-shadow:0 16px 50px rgba(0,240,255,.25);}
        .btn-hero-outline{display:inline-flex;align-items:center;gap:8px;background:transparent;padding:16px 36px;border-radius:100px;font-size:15px;text-decoration:none;transition:all .2s;}
        html:not(.dark) .btn-hero-outline{border:1px solid rgba(0,0,0,.12);color:#0F172A;}
        .dark .btn-hero-outline{border:1px solid rgba(255,255,255,.12);color:#EEF2FF;}
        .btn-hero-outline:hover{border-color:var(--cyan);color:var(--cyan);transform:translateY(-3px);}
        /* Stat cards hero */
        .hero-stat{border-radius:20px;padding:24px;text-align:center;}
        html:not(.dark) .hero-stat{background:rgba(255,255,255,.9);border:1px solid rgba(0,0,0,.06);backdrop-filter:blur(12px);}
        .dark .hero-stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);backdrop-filter:blur(12px);}
        /* Feature cards */
        .feat-card{border-radius:20px;padding:28px;transition:all .3s;}
        html:not(.dark) .feat-card{background:#fff;border:1px solid rgba(0,0,0,.07);box-shadow:0 4px 20px rgba(0,0,0,.05);}
        .dark .feat-card{background:var(--bg-panel);border:1px solid var(--border-w);}
        .feat-card:hover{transform:translateY(-6px);border-color:rgba(0,240,255,.25);}
        .feat-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:18px;}
        /* Plan preview cards */
        .plan-preview{border-radius:24px;padding:28px;transition:all .3s;}
        html:not(.dark) .plan-preview{background:#fff;border:1px solid rgba(0,0,0,.07);}
        .dark .plan-preview{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);}
        .plan-preview:hover{transform:translateY(-6px);border-color:rgba(0,240,255,.3);}
        .live-badge{display:inline-block;padding:3px 12px;border-radius:100px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;background:rgba(0,240,255,.1);color:var(--cyan);border:1px solid rgba(0,240,255,.2);}
        /* Sections */
        .section{max-width:1100px;margin:0 auto;padding:80px 28px;}
        /* Divider */
        .xr-divider{width:100%;height:1px;background:linear-gradient(90deg,transparent,rgba(0,240,255,.2),transparent);margin:0;}
        /* Bottom CTA */
        .bottom-cta{border-radius:32px;padding:72px 40px;text-align:center;position:relative;overflow:hidden;}
        html:not(.dark) .bottom-cta{background:linear-gradient(135deg,#EEF4FF,#F0EEFF);}
        .dark .bottom-cta{background:linear-gradient(135deg,rgba(0,240,255,.04),rgba(139,92,246,.04));border:1px solid rgba(0,240,255,.1);}
        /* Vignette */
        .vignette{position:fixed;inset:0;pointer-events:none;z-index:2;background:radial-gradient(ellipse 80% 80% at 50% 50%,transparent 50%,rgba(0,0,0,.4) 100%);opacity:0;}
        .dark .vignette{opacity:1;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{opacity:0;animation:fadeUp .6s forwards;}
        .d1{animation-delay:.1s}.d2{animation-delay:.25s}.d3{animation-delay:.4s}.d4{animation-delay:.55s}.d5{animation-delay:.7s}
        @media(max-width:768px){.hero-stats,.feat-grid,.plan-grid,.why-grid{grid-template-columns:1fr!important;}.section{padding:60px 20px;}}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="vignette"></div>
    <canvas id="wc"></canvas>
    <x-navbar :is-authenticated="auth()->check()" />

    {{-- ═══ HERO ═══ --}}
    <section class="hero">
        <div class="glow" style="width:700px;height:700px;top:-200px;left:-200px;background:rgba(0,240,255,.06)"></div>
        <div class="glow" style="width:600px;height:600px;bottom:-100px;right:-150px;background:rgba(139,92,246,.06)"></div>
        <div class="glow" style="width:400px;height:400px;top:30%;left:50%;transform:translateX(-50%);background:rgba(255,59,107,.04)"></div>

        <div style="max-width:860px;position:relative;z-index:10">
            <div class="fade-up d1" style="margin-bottom:28px">
                <span class="xr-chip chip-live"><span class="chip-dot"></span> Immersive XR Insurance</span>
            </div>
            <h1 class="syne fade-up d2" style="font-size:clamp(44px,7vw,88px);font-weight:800;letter-spacing:-3px;line-height:1.0;margin-bottom:24px">
                Protect what matters<br>in a <span class="grad-cv">new dimension.</span>
            </h1>
            <p class="fade-up d3 text-sub" style="font-size:clamp(16px,2vw,20px);max-width:580px;margin:0 auto 48px;line-height:1.7;font-weight:300">
                LifeShield XR combines cinematic visuals, instant plan insights, and reliable policy protection so your family stays secure in every reality.
            </p>
            <div class="fade-up d4" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;margin-bottom:72px">
                <a href="{{ route('plans.index') }}" class="btn-hero-primary">Browse Plans →</a>
                <a href="{{ route('vr') }}" class="btn-hero-outline">🥽 Launch AR Demo</a>
            </div>

            {{-- Live stats --}}
            <div class="hero-stats fade-up d5" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
                @foreach([
                    [number_format($planCount),'Available Plans','Tailored solutions from the database.','var(--cyan)'],
                    [number_format($policyCount),'Active Policies','Trusted coverage across customers.','var(--violet)'],
                    [number_format($activeUsers),'Members','Real families protected today.','var(--emerald)'],
                ] as [$num,$lbl,$desc,$col])
                <div class="hero-stat">
                    <div style="font-size:10px;text-transform:uppercase;letter-spacing:2px;font-weight:600;margin-bottom:8px" class="text-sub">{{ $lbl }}</div>
                    <div class="syne" style="font-size:clamp(28px,3.5vw,44px);font-weight:800;color:{{ $col }};line-height:1;margin-bottom:6px">{{ $num }}</div>
                    <div class="text-sub" style="font-size:12px">{{ $desc }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="xr-divider"></div>

    {{-- ═══ WHY LIFESHIELD ═══ --}}
    <section class="section">
        <div style="text-align:center;margin-bottom:56px">
            <span class="xr-chip chip-live"><span class="chip-dot"></span> Features</span>
            <h2 class="syne text-hi" style="font-size:clamp(28px,4vw,48px);font-weight:700;letter-spacing:-1.5px;margin-top:20px;margin-bottom:12px">Why Choose LifeShield XR?</h2>
            <p class="text-sub" style="font-size:16px;max-width:500px;margin:0 auto;line-height:1.65">Experience insurance like never before — immersive, instant, and intelligent.</p>
        </div>
        <div class="feat-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
            @foreach([
                ['⚡','Instant Coverage','rgba(0,240,255,.08)','Get immediate life insurance coverage with our streamlined digital process. No paperwork, no delays.','var(--cyan)'],
                ['🥽','AR/VR Experience','rgba(139,92,246,.08)','Explore policies in 3D with our immersive augmented reality demonstrations and scenario simulations.','var(--violet)'],
                ['💰','Flexible Premiums','rgba(0,230,118,.08)','Choose from various payment options that fit your budget and lifestyle needs perfectly.','var(--emerald)'],
                ['🕐','24/7 AI Support','rgba(255,183,0,.08)','Round-the-clock customer support with AI-powered assistance and AR-guided help sessions.','var(--amber)'],
                ['🔒','Secure & Trusted','rgba(255,59,107,.08)','Bank-grade security with blockchain-verified policy management and encrypted data storage.','var(--rose)'],
                ['⚡','Quick Claims','rgba(0,240,255,.08)','Fast-track claim processing with AI-powered document verification settled in under 60 seconds.','var(--cyan)'],
            ] as [$ic,$ti,$bg,$de,$col])
            <div class="feat-card">
                <div class="feat-icon" style="background:{{ $bg }}">{{ $ic }}</div>
                <h3 class="syne text-hi" style="font-size:17px;font-weight:600;margin-bottom:10px">{{ $ti }}</h3>
                <p class="text-sub" style="font-size:13px;line-height:1.65">{{ $de }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <div class="xr-divider"></div>

    {{-- ═══ LIVE PLANS ═══ --}}
    <section class="section">
        <div style="text-align:center;margin-bottom:56px">
            <span class="xr-chip chip-live"><span class="chip-dot"></span> Live Plans</span>
            <h2 class="syne text-hi" style="font-size:clamp(28px,4vw,48px);font-weight:700;letter-spacing:-1.5px;margin-top:20px;margin-bottom:12px">Database-backed Plans Shown Live.</h2>
            <p class="text-sub" style="font-size:16px;max-width:520px;margin:0 auto;line-height:1.65">Every plan tile reflects the current database inventory so users see accurate coverage data.</p>
        </div>
        <div class="plan-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
            @foreach($plans as $plan)
            @php $colors=['var(--cyan)','var(--violet)','var(--rose)','var(--amber)','var(--emerald)'];$col=$colors[$loop->index%count($colors)]; @endphp
            <div class="plan-preview">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px">
                    <div>
                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:2px;color:{{ $col }};font-weight:700;margin-bottom:6px">{{ $plan->duration_years }}yr term</div>
                        <h3 class="syne text-hi" style="font-size:20px;font-weight:700">{{ $plan->name }}</h3>
                    </div>
                    <span class="live-badge">Live</span>
                </div>
                <p class="text-sub" style="font-size:13px;line-height:1.6;margin-bottom:20px">{{ Str::limit($plan->description,90) }}</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px">
                    <div style="border-radius:12px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06)">
                        <div class="text-sub" style="font-size:10px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Coverage</div>
                        <div class="syne text-hi" style="font-size:16px;font-weight:700">₹{{ number_format($plan->coverage_amount) }}</div>
                    </div>
                    <div style="border-radius:12px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06)">
                        <div class="text-sub" style="font-size:10px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Premium</div>
                        <div class="syne" style="font-size:16px;font-weight:700;color:{{ $col }}">₹{{ number_format($plan->premium_amount) }}</div>
                    </div>
                </div>
                <a href="{{ route('plans.show',$plan) }}" style="display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:11px;border-radius:100px;font-size:13px;font-weight:700;text-decoration:none;background:{{ $col }};color:#020F14;transition:all .2s"
                   onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">View Plan →</a>
            </div>
            @endforeach
        </div>
    </section>

    <div class="xr-divider"></div>

    {{-- ═══ XR DIFFERENCE ═══ --}}
    <section class="section">
        <div style="border-radius:28px;padding:40px;background:linear-gradient(135deg,rgba(0,240,255,.04),rgba(139,92,246,.04));border:1px solid rgba(0,240,255,.1)">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:32px;align-items:center" class="why-grid">
                <div>
                    <span class="xr-chip chip-live" style="margin-bottom:16px;display:inline-flex"><span class="chip-dot"></span> Different</span>
                    <h2 class="syne text-hi" style="font-size:clamp(22px,3vw,32px);font-weight:700;letter-spacing:-1px">A modern insurance experience built for XR.</h2>
                </div>
                <div class="text-sub" style="font-size:14px;line-height:1.75">
                    <p style="margin-bottom:12px">Smart coverage recommendations based on actual plan data and live policy trends.</p>
                    <p>Live database values ensure every page reflects real plan availability and real customer counts.</p>
                </div>
                <div style="text-align:right">
                    <a href="{{ route('contact') }}" style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:100px;background:linear-gradient(135deg,var(--cyan),var(--violet));color:#fff;font-weight:700;font-size:14px;text-decoration:none;transition:all .2s"
                       onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">Talk to an Expert →</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ BOTTOM CTA ═══ --}}
    <section class="section" style="padding-top:0">
        <div class="bottom-cta">
            <span class="xr-chip chip-live" style="display:inline-flex;margin-bottom:24px"><span class="chip-dot"></span> Get Started</span>
            <h2 class="syne grad-all" style="font-size:clamp(36px,5vw,64px);font-weight:800;letter-spacing:-2px;line-height:1.05;margin-bottom:16px">
                Protect your family<br>starting today.
            </h2>
            <p class="text-sub" style="font-size:16px;margin-bottom:36px;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.65">No paperwork. No waiting. AR-powered onboarding in under 60 seconds.</p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
                <a href="{{ route('plans.index') }}" class="btn-hero-primary">Browse Plans →</a>
                @guest<a href="{{ route('register') }}" class="btn-hero-outline">Create Free Account</a>@endguest
            </div>
        </div>
    </section>

    <footer style="text-align:center;padding:28px;font-size:13px;border-top:1px solid rgba(255,255,255,.05)" class="text-sub">
        © {{ date('Y') }} LifeShield XR. All rights reserved.
    </footer>

    <script>
    (function(){
        const c=document.getElementById('wc'),ctx=c.getContext('2d');let W,H;
        const resize=()=>{W=c.width=window.innerWidth;H=c.height=window.innerHeight;};
        window.addEventListener('resize',resize);resize();
        const isDark=()=>document.documentElement.classList.contains('dark');
        class P{constructor(){this.reset();}reset(){this.x=Math.random()*W;this.y=Math.random()*H;this.r=Math.random()*1.5+.3;this.vx=(Math.random()-.5)*.22;this.vy=(Math.random()-.5)*.22;this.a=Math.random()*.35+.08;this.col=Math.random()>.6?'#00F0FF':Math.random()>.5?'#8B5CF6':'#ffffff';}update(){this.x+=this.vx;this.y+=this.vy;if(this.x<0||this.x>W||this.y<0||this.y>H)this.reset();}draw(){ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);ctx.fillStyle=this.col;ctx.globalAlpha=this.a;ctx.fill();}}
        const ps=Array.from({length:80},()=>new P());
        const loop=()=>{if(!isDark()){ctx.clearRect(0,0,W,H);requestAnimationFrame(loop);return;}ctx.clearRect(0,0,W,H);ctx.globalAlpha=1;for(let i=0;i<ps.length;i++)for(let j=i+1;j<ps.length;j++){const dx=ps[i].x-ps[j].x,dy=ps[i].y-ps[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<100){ctx.beginPath();ctx.moveTo(ps[i].x,ps[i].y);ctx.lineTo(ps[j].x,ps[j].y);ctx.strokeStyle='rgba(0,240,255,'+(0.03*(1-d/100))+')';ctx.lineWidth=.5;ctx.stroke();}}ps.forEach(p=>{p.update();p.draw();});requestAnimationFrame(loop);};loop();
    })();
    </script>
</body>
</html>