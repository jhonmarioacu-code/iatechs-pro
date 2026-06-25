<button
    type="button"
    class="ai-fab"
    x-show="assistantEnabled"
    @click="toggleAssistantPanel()"
    aria-label="Abrir IA Assistant"
>
    IA
</button>

<aside class="ai-panel" x-show="assistantPanelOpen" x-transition.opacity>
    <header>
        <h3>IA Assistant</h3>
        <button type="button" @click="toggleAssistantPanel()" aria-label="Cerrar IA">✕</button>
    </header>
    <p>Asistente disponible en todos los módulos autorizados.</p>
    <ul>
        <li>Diagnósticos asistidos</li>
        <li>Resumen de tickets</li>
        <li>Sugerencias operativas</li>
    </ul>
</aside>

