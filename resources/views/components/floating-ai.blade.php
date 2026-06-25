@props([
    'portal' => 'admin',
    'enabled' => false,
])

@php
    $routes = match ($portal) {
        'admin' => [
            'chat' => '/admin/ai/chat',
            'conversations' => '/admin/ai/conversations',
            'messages' => route('admin.ai.messages', ['conversation' => '__CONVERSATION__']),
        ],
        'company' => [
            'chat' => route('portal.company.ai.chat'),
            'conversations' => route('portal.company.ai.conversations'),
            'messages' => route('portal.company.ai.messages', ['conversation' => '__CONVERSATION__']),
        ],
        'technician' => [
            'chat' => route('portal.technician.ai.chat'),
            'conversations' => route('portal.technician.ai.conversations'),
            'messages' => route('portal.technician.ai.messages', ['conversation' => '__CONVERSATION__']),
        ],
        default => [
            'chat' => '',
            'conversations' => '',
            'messages' => '',
        ],
    };
@endphp

<div
    data-ai-enabled="{{ $enabled ? '1' : '0' }}"
    data-ai-chat-url="{{ $routes['chat'] }}"
    data-ai-conversations-url="{{ $routes['conversations'] }}"
    data-ai-messages-url-template="{{ $routes['messages'] }}"
>
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
            <button type="button" @click="toggleAssistantPanel()" aria-label="Cerrar IA">x</button>
        </header>

        <p x-show="assistantError" x-text="assistantError"></p>

        <div x-show="assistantEnabled" style="margin: 8px 0;">
            <label for="ai-conversation-select"><strong>Conversacion</strong></label>
            <select
                id="ai-conversation-select"
                x-model="assistantConversationId"
                @change="loadSelectedConversationMessages()"
                style="width: 100%; margin-top: 6px;"
            >
                <option value="">Nueva conversacion</option>
                <template x-for="item in assistantConversations" :key="item.id">
                    <option :value="String(item.id)" x-text="conversationLabel(item)"></option>
                </template>
            </select>
        </div>

        <div style="max-height: 220px; overflow-y: auto; margin: 12px 0;">
            <template x-for="(item, index) in assistantMessages" :key="index">
                <div style="margin-bottom: 10px;">
                    <strong x-text="item.role === 'assistant' ? 'IA' : 'Tu'"></strong>
                    <p x-text="item.content" style="margin: 4px 0 0 0;"></p>
                </div>
            </template>
            <p x-show="assistantMessages.length === 0">Haz una pregunta y te respondo segun tu rol.</p>
        </div>

        <form @submit.prevent="sendAssistantMessage()">
            <textarea
                x-model="assistantInput"
                placeholder="Escribe tu consulta..."
                rows="3"
                style="width: 100%;"
            ></textarea>
            <button type="submit" :disabled="assistantLoading || assistantInput.trim() === ''">
                <span x-show="!assistantLoading">Enviar</span>
                <span x-show="assistantLoading">Enviando...</span>
            </button>
        </form>
    </aside>
</div>
