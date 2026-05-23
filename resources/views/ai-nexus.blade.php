<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nexus AI Intel — {{ config('app.name', 'LifeShield XR') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>(function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--cyan:#00F0FF;--violet:#8B5CF6;--rose:#FF3B6B;--emerald:#00E676;--amber:#FFB700;--bg-void:#03060F;--bg-deep:#060C1A;--bg-panel:rgba(8,14,30,.92);--text-mid:#8892AA;--border:rgba(0,240,255,.1);--border-w:rgba(255,255,255,.07);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;transition:background .3s,color .3s;overflow-x:hidden;}
        html:not(.dark) body{background:#F0F4FF;color:#0F172A;}
        .dark body{background:var(--bg-void);color:#EEF2FF;}
        .syne{font-family:'Syne',sans-serif;}
        .vr-scanlines{display:none;position:fixed;inset:0;pointer-events:none;z-index:1;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.025) 2px,rgba(0,0,0,.025) 4px);}
        .dark .vr-scanlines{display:block;}
        .glow{position:fixed;border-radius:50%;filter:blur(120px);pointer-events:none;z-index:0;opacity:0;transition:opacity .5s;}
        .dark .glow{opacity:1;}

        /* Layout Framework */
        .page-wrap{max-width:1200px;margin:0 auto;padding:80px 28px 60px;position:relative;z-index:10;}
        .nexus-grid{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:stretch;}

        /* Chat Interface Styles */
        .chat-container{border-radius:24px;display:flex;flex-direction:column;height:600px;position:relative;overflow:hidden;backdrop-filter:blur(20px);}
        html:not(.dark) .chat-container{background:#fff;border:1px solid rgba(0,0,0,.08);box-shadow:0 10px 30px rgba(0,0,0,.04);}
        .dark .chat-container{background:var(--bg-panel);border:1px solid var(--border-w);box-shadow:0 20px 50px rgba(0,0,0,.3);}

        .chat-stream{flex:1;overflow-y:auto;padding:24px;display:flex;flex-direction:column;gap:18px;}
        /* Custom Scrollbar */
        .chat-stream::-webkit-scrollbar{width:6px;}
        .chat-stream::-webkit-scrollbar-track{background:transparent;}
        .chat-stream::-webkit-scrollbar-thumb{border-radius:10px;}
        html:not(.dark) .chat-stream::-webkit-scrollbar-thumb{background:rgba(0,0,0,.1);}
        .dark .chat-stream::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);}

        /* Speech Bubbles */
        .msg-row{display:flex;width:100%;animation:msgFadeUp .3s forwards;}
        .msg-row.incoming{justify-content:flex-start;}
        .msg-row.outgoing{justify-content:flex-end;}

        .bubble{max-width:75%;padding:14px 18px;border-radius:18px;font-size:14px;line-height:1.5;position:relative;}
        .incoming .bubble{border-radius:4px 18px 18px 18px;}
        html:not(.dark) .incoming .bubble{background:#F1F5F9;color:#1E293B;}
        .dark .incoming .bubble{background:rgba(255,255,255,.03);color:#E2E8F0;border:1px solid rgba(255,255,255,.04);}

        .outgoing .bubble{border-radius:18px 18px 4px 18px;background:linear-gradient(135deg, var(--violet), #6D28D9);color:#fff;}

        /* Console Input Block */
        .input-tray{padding:16px 24px;border-top:1px solid;display:flex;gap:12px;align-items:center;}
        html:not(.dark) .input-tray{border-color:rgba(0,0,0,.06);background:#FAFCFF;}
        .dark .input-tray{border-color:var(--border-w);background:rgba(3,6,15,.4);}

        .console-input{flex:1;background:transparent;border:none;outline:none;font-size:14px;padding:8px 0;}
        html:not(.dark) .console-input{color:#0F172A;}
        .dark .console-input{color:#EEF2FF;}

        /* Suggestion Chips */
        .suggest-pill{padding:8px 16px;border-radius:100px;font-size:12px;cursor:pointer;transition:all .2s;white-space:nowrap;display:inline-block;}
        html:not(.dark) .suggest-pill{background:#fff;border:1px solid rgba(0,0,0,.1);color:#475569;}
        .dark .suggest-pill{background:rgba(255,255,255,.03);border:1px solid var(--border-w);color:var(--text-mid);}
        .suggest-pill:hover{border-color:var(--cyan);color:var(--cyan);transform:translateY(-1px);}

        /* Cards & Components */
        .xr-card{border-radius:20px;padding:24px;position:relative;overflow:hidden;}
        html:not(.dark) .xr-card{background:#fff;border:1px solid rgba(0,0,0,.07);box-shadow:0 4px 20px rgba(0,0,0,.06);}
        .dark .xr-card{background:var(--bg-panel);border:1px solid var(--border-w);}

        .xr-chip{display:inline-flex;align-items:center;gap:8px;border-radius:100px;padding:4px 14px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;background:rgba(0,240,255,.08);border:1px solid rgba(0,240,255,.2);color:var(--cyan);}
        .chip-dot{width:6px;height:6px;background:var(--cyan);border-radius:50%;animation:blink 1.5s infinite;}
        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}
        .agent-node-card{transition:all .2s;cursor:pointer;}
        .agent-node-card:hover { border-color: rgba(0,240,255,0.3) !important; background: rgba(255,255,255,0.02); }
        .agent-node-card.active { border-color: var(--cyan) !important; background: rgba(0,240,255,0.04); box-shadow: 0 0 15px rgba(0,240,255,0.1); }
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
        @keyframes msgFadeUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        @media(max-width:900px){.nexus-grid{grid-template-columns:1fr!important;}}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:500px;height:500px;top:-100px;left:-100px;background:rgba(0,240,255,.06)"></div>
    <div class="glow" style="width:600px;height:600px;bottom:0;right:-150px;background:rgba(139,92,246,.04)"></div>

    {{-- FIX: Pass is-admin prop so admin navbar styling works on this page --}}
    <x-navbar
        :is-authenticated="auth()->check()"
        :is-admin="auth()->check() && (auth()->user()->role === 'admin' || (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()))"
    />

    <div class="page-wrap">

        {{-- Header Section --}}
        <div style="margin-bottom:32px">
            <span class="xr-chip"><span class="chip-dot"></span> LifeShield Nexus Engine</span>
            <h1 class="syne text-hi" style="font-size:clamp(24px,4vw,40px);font-weight:800;letter-spacing:-1.2px;margin-top:12px;margin-bottom:6px">
                Nexus Core <span style="color:var(--cyan)">AI Intel</span>
            </h1>
            <p class="text-sub" style="font-size:14px">Real-time claim structural audits, instant policy assessments, and synthetic risk generation diagnostics.</p>
        </div>

        <div class="nexus-grid">

            {{-- Left Side: The Nexus AI Chat Core --}}
            <div class="chat-container">
                {{-- FIX: justify-content:between → justify-content:space-between (valid CSS) --}}
                <div style="padding:16px 24px;border-bottom:1px solid;display:flex;align-items:center;justify-content:space-between;" class="dark:border-white/5 border-slate-100">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--emerald);box-shadow:0 0 10px var(--emerald)"></div>
                        <span class="syne text-hi" style="font-weight:600;font-size:14px;letter-spacing:0.5px">NEXUS_V1.8_ONLINE</span>
                    </div>
                    <span class="text-sub" style="font-size:11px;font-family:monospace;">SECURE_SESSION_ENCRYPTED</span>
                </div>

                {{-- Scrolling Message Area --}}
                <div class="chat-stream" id="chatStream">
                    <!-- Base Assistant Greeting Bubble -->
                    <div class="msg-row incoming">
                        <div class="bubble">
                            Greetings, <strong>{{ auth()->user()->name }}</strong>. I am the <strong>LifeShield Nexus AI</strong>. I have indexed your current active coverage profiles. You can ask me to evaluate claims documents, simulate environmental structural damage for an AR validation run, or analyze adjustments to your monthly premiums. How shall we proceed?
                        </div>
                    </div>
                </div>

                {{-- Interactive Shortcuts Prompt Area --}}
                <div style="padding:0 24px 12px;overflow-x:auto;display:flex;gap:8px;" id="suggestionContainer">
                    <span class="suggest-pill" onclick="sendSuggestion('Audit my existing coverage limits')">🔍 Audit Coverage</span>
                    <span class="suggest-pill" onclick="sendSuggestion('File a synthetic claims incident blueprint')">⚡ Blueprint Incident</span>
                    <span class="suggest-pill" onclick="sendSuggestion('How do I run an AR hardware simulation?')">🥽 AR Setup Intel</span>
                </div>

                {{-- Console Form Input Tray --}}
                <form id="nexusForm" onsubmit="handleFormSubmit(event)">
                    <div class="input-tray">
                        <span style="font-size:16px;opacity:0.7">🤖</span>
                        <input type="text" id="userInput" class="console-input" placeholder="Transmit direct query parameter to Nexus Core..." autocomplete="off" required>
                        <button type="submit" style="background:var(--cyan);color:#020F14;border:none;padding:8px 20px;border-radius:100px;font-size:12px;font-weight:700;cursor:pointer;transition:transform 0.2s;" id="sendBtn">
                            TRANSMIT →
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right Side: Active Neural Agents Deck --}}
            <div style="display:flex;flex-direction:column;gap:20px">
                <div class="xr-card">
                    <h3 class="syne text-hi" style="font-weight:600;font-size:16px;margin-bottom:4px">Neural Agent Mesh</h3>
                    <p class="text-sub" style="font-size:12px;margin-bottom:16px">Select a dedicated subprocess sub-agent node to delegate your operational queries:</p>

                    <div style="display:flex;flex-direction:column;gap:12px" id="agentSelectorGroup">
                        @foreach($agents as $key => $agent)
                            <div class="agent-node-card {{ $loop->first ? 'active' : '' }}"
                                 data-agent-key="{{ $key }}"
                                 data-agent-name="{{ $agent['name'] }}"
                                 data-agent-badge="{{ $agent['badge'] }}"
                                 onclick="switchAgent(this)"
                                 style="border: 1px solid var(--border-w); padding: 14px; border-radius: 14px;">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
                                    <span class="syne text-hi" style="font-weight:600; font-size:13px;">{{ $agent['icon'] }} {{ $agent['name'] }}</span>
                                    <span style="font-size:9px; font-weight:bold; color:{{ $agent['color'] }}; background:rgba(255,255,255,0.03); padding:2px 8px; border-radius:4px; letter-spacing:0.5px;">{{ $agent['badge'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Frontend Interactivity & Non-blocking Stream Communication Script --}}
    <script>
        let activeAgentKey = '{{ array_key_first($agents) }}';

        function switchAgent(element) {
            document.querySelectorAll('.agent-node-card').forEach(card => card.classList.remove('active'));
            element.classList.add('active');
            activeAgentKey = element.getAttribute('data-agent-key');
            const agentName = element.getAttribute('data-agent-name');
            appendMessage(`<em>🔄 System routing altered: Core handoff executed to channel node [<strong>${agentName}</strong>]. Ready for localized operations.</em>`, 'incoming');
        }

        function appendMessage(text, side) {
            const stream = document.getElementById('chatStream');
            const row = document.createElement('div');
            row.className = `msg-row ${side}`;
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.innerHTML = text;
            row.appendChild(bubble);
            stream.appendChild(row);
            stream.scrollTop = stream.scrollHeight;
        }

        function sendSuggestion(text) {
            document.getElementById('userInput').value = text;
            document.getElementById('nexusForm').dispatchEvent(new Event('submit'));
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            const inputField = document.getElementById('userInput');
            const prompt = inputField.value.trim();
            if(!prompt) return;

            appendMessage(prompt, 'outgoing');
            inputField.value = '';

            const btn = document.getElementById('sendBtn');
            btn.disabled = true;
            btn.innerText = 'PROCESSING...';

            fetch(`{{ url('/ai-nexus/chat') }}/${activeAgentKey}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: prompt })
            })
            .then(res => {
                if(!res.ok) throw new Error();
                return res.json();
            })
            .then(data => {
                appendMessage(data.reply, 'incoming');
            })
            .catch(() => {
                appendMessage("<span style='color:var(--rose)'>⚠️ Critical Nexus uplink disruption. Unable to process neural weights. Please re-verify localized environment pipelines.</span>", 'incoming');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'TRANSMIT →';
            });
        }
    </script>
</body>
</html>