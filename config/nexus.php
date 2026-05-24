<?php

return [
    'agents' => [
        'rag_agent' => [
            'name' => 'RAG Agent',
            'icon' => '🔍',
            'badge' => 'AUDIT_ACTIVE',
            'color' => 'var(--rose)',
            'system_instruction' => 'You are the LifeShield Claims Auditor. Analyze telemetry data and damage reports to verify claims validity and catch discrepancies.',
        ],
        'claim_agent' => [
            'name' => 'Claim Evaluator',
            'icon' => '⚡',
            'badge' => 'SIM_ENGINE_READY',
            'color' => 'var(--cyan)',
            'system_instruction' => 'You are the LifeShield Spatial Risk Simulator. Run calculations on environmental factors, AR tracking profiles, and synthetic accident blueprints.',
        ],
        'support_agent' => [
            'name' => 'Policy Coverage Matcher',
            'icon' => '📋',
            'badge' => 'MATRICES_OPTIMIZED',
            'color' => 'var(--violet)',
            'system_instruction' => 'You are the LifeShield Policy Coverage Matcher. Help users adjust premium pricing structures and match them to perfect liability configurations.',

        ],
        'matcher' => [
            'name' => 'Intent Matcher',
            'color' => '#eab308', // Yellow
            'icon' => '🎯',
            'badge' => 'ORCHESTRATOR',
        ],
        'auditor' => [
            'name' => 'Compliance Auditor',
            'color' => '#ef4444', // Red
            'icon' => '🔍',
            'badge' => 'AUDIT',
        ],
    ]
];