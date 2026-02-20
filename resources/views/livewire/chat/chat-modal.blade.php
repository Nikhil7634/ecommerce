<div x-data="{
    isOpen: @entangle('isOpen'),
    quickQuestions: [
        'What is the delivery time?',
        'Is this product in stock?',
        'Can I get a discount on bulk order?',
        'What is the warranty period?',
        'Do you offer installation service?',
        'What are the payment options?',
        'What is your return policy?',
        'Do you provide technical support?'
    ],
    selectedQuickQuestions: [],
    showQuickQuestions: true,
    typing: false,
    messageLength: 0,
    maxLength: 240,
    isAttachmentMenuOpen: false,
    activeEmojiTab: 'smileys',
    emojiCategories: {
        'smileys': ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰'],
        'hands': ['👍', '👎', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✌️', '🤞', '🤟', '🤘', '👌', '🤌', '🤙', '👈'],
        'objects': ['📱', '💻', '⌚', '📷', '🎧', '📚', '✏️', '📌', '📍', '📎', '🖇️', '📁', '📂', '🗂️', '📅', '📆'],
        'symbols': ['❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖']
    },
    init() {
        this.messageLength = $wire.message.length;
        
        this.$watch('isOpen', (value) => {
            if (value) {
                setTimeout(() => {
                    this.$refs.messageInput?.focus();
                    this.scrollToBottom();
                }, 300);
            } else {
                this.isAttachmentMenuOpen = false;
            }
        });
        
        this.$watch('$wire.message', (value) => {
            this.messageLength = value.length;
        });
        
        Livewire.on('message-sent', () => {
            this.showQuickQuestions = false;
            setTimeout(() => this.scrollToBottom(), 100);
        });
        
        Livewire.on('message-received', () => {
            setTimeout(() => this.scrollToBottom(), 100);
        });
        
        Livewire.on('typing-start', () => {
            this.typing = true;
        });
        
        Livewire.on('typing-stop', () => {
            this.typing = false;
        });
        
        this.$nextTick(() => {
            document.addEventListener('click', (e) => {
                if (!this.$refs.attachmentMenu?.contains(e.target) && !this.$refs.attachmentBtn?.contains(e.target)) {
                    this.isAttachmentMenuOpen = false;
                }
            });
        });
    },
    scrollToBottom() {
        const container = this.$refs.messagesContainer;
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 50);
        }
    },
    sendQuickQuestion(question) {
        if (this.selectedQuickQuestions.includes(question)) return;
        
        this.selectedQuickQuestions.push(question);
        $wire.set('message', question);
        $wire.sendMessage();
        
        setTimeout(() => {
            const index = this.quickQuestions.indexOf(question);
            if (index > -1) {
                this.quickQuestions.splice(index, 1);
            }
        }, 500);
    },
    formatTime(time) {
        return new Date(time).toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
    },
    formatDate(date) {
        const messageDate = new Date(date);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        if (messageDate.toDateString() === today.toDateString()) {
            return 'Today';
        } else if (messageDate.toDateString() === yesterday.toDateString()) {
            return 'Yesterday';
        } else {
            return messageDate.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: messageDate.getFullYear() !== today.getFullYear() ? 'numeric' : undefined
            });
        }
    },
    insertEmoji(emoji) {
        $wire.set('message', $wire.message + emoji);
        this.$refs.messageInput.focus();
    },
    toggleAttachmentMenu() {
        this.isAttachmentMenuOpen = !this.isAttachmentMenuOpen;
    },
    handleKeydown(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if ($wire.message.trim()) {
                $wire.sendMessage();
            }
        }
        
        if (e.key === 'Escape') {
            if (this.isAttachmentMenuOpen) {
                this.isAttachmentMenuOpen = false;
            } else {
                $wire.closeModal();
            }
        }
    }
}" 
x-on:keydown.escape="isOpen = false"
class="whatsapp-chat-container">
    <!-- Chat Modal -->
    <div class="whatsapp-modal-overlay" x-show="isOpen" 
         x-cloak
         x-transition:enter="whatsapp-modal-enter"
         x-transition:enter-start="whatsapp-modal-enter-start"
         x-transition:enter-end="whatsapp-modal-enter-end"
         x-transition:leave="whatsapp-modal-leave"
         x-transition:leave-start="whatsapp-modal-leave-start"
         x-transition:leave-end="whatsapp-modal-leave-end">
        
        <div class="whatsapp-modal-backdrop" @click="$wire.closeModal()"></div>
        
        <div class="whatsapp-modal-wrapper">
            <div class="whatsapp-modal-container">
                <!-- Header -->
                <div class="whatsapp-header">
                    <div class="whatsapp-header-content">
                        <div class="whatsapp-header-left">
                            <button type="button" 
                                    class="whatsapp-back-btn"
                                    @click="$wire.closeModal()"
                                    title="Back">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            
                            <div class="whatsapp-avatar-container">
                                <div class="whatsapp-avatar">
                                    @if($adminUser && $adminUser->avatar)
                                        <img src="{{ asset('storage/' . $adminUser->avatar) }}" alt="Admin">
                                    @else
                                        <i class="fas fa-user-shield"></i>
                                    @endif
                                </div>
                                <div class="whatsapp-status-indicator">
                                    <div class="whatsapp-status-dot"></div>
                                </div>
                            </div>
                            
                            <div class="whatsapp-user-info">
                                <h3 class="whatsapp-user-name">Admin Support</h3>
                                <div class="whatsapp-user-status">
                                    <span class="whatsapp-status-dot"></span>
                                    <span style="color: white">online • replies instantly</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="whatsapp-header-actions">
                            <button type="button" 
                                    class="whatsapp-header-btn"
                                    @click="$wire.clearChat()"
                                    title="Clear chat">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <button type="button" 
                                    class="whatsapp-header-btn"
                                    title="Chat info">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button type="button" 
                                    class="whatsapp-header-btn whatsapp-close-btn"
                                    @click="$wire.closeModal()"
                                    title="Close chat">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if($product)
                    <div class="whatsapp-product-info">
                        <div class="whatsapp-product-left">
                            <div class="whatsapp-product-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="whatsapp-product-details">
                                <div class="whatsapp-product-name">{{ $product->name }}</div>
                                <div class="whatsapp-product-category">{{ $product->category->name ?? 'Product' }}</div>
                            </div>
                        </div>
                        
                        <div class="whatsapp-product-price">
                                @if($product->sale_price)
                                    ₹{{ number_format($product->final_sale_price ?? $product->sale_price, 2) }} 
                                    <del>₹{{ number_format($product->final_base_price ?? $product->base_price, 2) }}</del>
                                @else
                                    ₹{{ number_format($product->final_base_price ?? $product->base_price, 2) }}
                                @endif
                        </div>
                        
                    </div>
                    @endif
                </div>

                <!-- Chat Body -->
                <div class="whatsapp-chat-body">
                    <div class="whatsapp-chat-background"></div>
                    
                    <div class="whatsapp-messages-container" x-ref="messagesContainer">
                        <div class="whatsapp-messages-content">
                            <!-- Welcome Message -->
                            <div class="whatsapp-welcome-container" x-show="showQuickQuestions && $wire.messages.length === 0">
                                <div class="whatsapp-welcome-card">
                                    <div class="whatsapp-welcome-header">
                                        <div class="whatsapp-welcome-avatar">
                                            <i class="fas fa-comment-alt"></i>
                                        </div>
                                        <div class="whatsapp-welcome-info">
                                            <h4>Welcome to Support Chat</h4>
                                            <div class="whatsapp-welcome-subtitle">
                                                <i class="fas fa-bolt"></i>
                                                <span>Usually replies within 2 minutes</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="whatsapp-welcome-message">
                                        Hello <span class="whatsapp-welcome-user">
                                        @auth
                                            {{ auth()->user()->name ?? 'there' }}
                                        @else
                                            Guest
                                        @endauth
                                        </span>! 👋 How can we help you today?
                                    </p>
                                    
                                    <div class="whatsapp-quick-questions-section">
                                        <p class="whatsapp-quick-questions-title">Quick questions:</p>
                                        <div class="whatsapp-quick-questions-grid">
                                            <template x-for="(question, index) in quickQuestions" :key="index">
                                                <button type="button"
                                                        class="whatsapp-quick-question-btn"
                                                        @click="sendQuickQuestion(question)"
                                                        :disabled="selectedQuickQuestions.includes(question)">
                                                    <div class="whatsapp-quick-question-content">
                                                        <span class="whatsapp-quick-question-text" x-text="question"></span>
                                                        <div class="whatsapp-quick-question-arrow">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </div>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <div class="whatsapp-security-notice">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Your messages are end-to-end encrypted</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Messages -->
                            <template x-if="$wire.messages.length > 0">
                                <div class="whatsapp-messages-list">
                                    <template x-for="(message, index) in $wire.messages" :key="message.id">
                                        <div class="whatsapp-message-group">
                                            <template x-if="index === 0 || formatDate(message.created_at) !== formatDate($wire.messages[index-1].created_at)">
                                                <div class="whatsapp-date-separator">
                                                    <span class="whatsapp-date-badge" x-text="formatDate(message.created_at)"></span>
                                                </div>
                                            </template>
                                            
                                            <div class="whatsapp-message" :class="message.is_sent_by_me ? 'whatsapp-message-sent' : 'whatsapp-message-received'">
                                                <div class="whatsapp-message-content">
                                                    <template x-if="!message.is_sent_by_me">
                                                        <div class="whatsapp-message-inner received">
                                                            <div class="whatsapp-message-avatar">
                                                                <i class="fas fa-user-shield"></i>
                                                            </div>
                                                            <div>
                                                                <div class="whatsapp-message-bubble whatsapp-message-bubble-received">
                                                                    <p class="whatsapp-message-text" x-text="message.message"></p>
                                                                </div>
                                                                <div class="whatsapp-message-time">
                                                                    <span x-text="formatTime(message.created_at)"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="message.is_sent_by_me">
                                                        <div class="whatsapp-message-inner sent">
                                                            <div>
                                                                <div class="whatsapp-message-bubble whatsapp-message-bubble-sent">
                                                                    <p class="whatsapp-message-text" x-text="message.message"></p>
                                                                </div>
                                                                <div class="whatsapp-message-time">
                                                                    <span x-text="formatTime(message.created_at)"></span>
                                                                    <div class="whatsapp-message-status">
                                                                        <i class="fas fa-check" :class="message.is_read ? 'whatsapp-status-read' : 'whatsapp-status-delivered'"></i>
                                                                        <i class="fas fa-check" :class="message.is_read ? 'whatsapp-status-read' : 'whatsapp-status-delivered'"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Typing Indicator -->
                            <div class="whatsapp-typing-indicator" x-show="typing">
                                <div class="whatsapp-typing-avatar">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="whatsapp-typing-bubble">
                                    <div class="whatsapp-typing-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sending Indicator -->
                            <div class="whatsapp-sending-indicator" x-show="$wire.sending">
                                <div class="whatsapp-sending-bubble">
                                    <div class="whatsapp-sending-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input Area -->
                    <div class="whatsapp-input-area">
                        <!-- Attachment Menu -->
                        <div class="whatsapp-attachment-menu" 
                             x-show="isAttachmentMenuOpen"
                             x-ref="attachmentMenu"
                             x-transition:enter="whatsapp-attachment-enter"
                             x-transition:enter-start="whatsapp-attachment-enter-start"
                             x-transition:enter-end="whatsapp-attachment-enter-end"
                             x-transition:leave="whatsapp-attachment-leave"
                             x-transition:leave-start="whatsapp-attachment-leave-start"
                             x-transition:leave-end="whatsapp-attachment-leave-end">
                            
                            <div class="whatsapp-emoji-picker">
                                <div class="whatsapp-emoji-tabs">
                                    <template x-for="(emojis, category) in emojiCategories" :key="category">
                                        <button type="button"
                                                class="whatsapp-emoji-tab"
                                                :class="{ 'whatsapp-emoji-tab-active': activeEmojiTab === category }"
                                                @click="activeEmojiTab = category">
                                            <i class="fas" :class="{
                                                'fa-smile': category === 'smileys',
                                                'fa-hand-peace': category === 'hands',
                                                'fa-cube': category === 'objects',
                                                'fa-heart': category === 'symbols'
                                            }"></i>
                                        </button>
                                    </template>
                                </div>
                                <div class="whatsapp-emoji-grid">
                                    <template x-for="emoji in emojiCategories[activeEmojiTab]" :key="emoji">
                                        <button type="button"
                                                class="whatsapp-emoji-btn"
                                                @click="insertEmoji(emoji)">
                                            <span x-text="emoji"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="whatsapp-attachment-options">
                                <button type="button" class="whatsapp-attachment-option">
                                    <div class="whatsapp-attachment-icon whatsapp-attachment-photos">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <span class="whatsapp-attachment-label">Photo & Video</span>
                                </button>
                                <button type="button" class="whatsapp-attachment-option">
                                    <div class="whatsapp-attachment-icon whatsapp-attachment-documents">
                                        <i class="fas fa-file"></i>
                                    </div>
                                    <span class="whatsapp-attachment-label">Document</span>
                                </button>
                                <button type="button" class="whatsapp-attachment-option">
                                    <div class="whatsapp-attachment-icon whatsapp-attachment-camera">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <span class="whatsapp-attachment-label">Camera</span>
                                </button>
                                <button type="button" class="whatsapp-attachment-option">
                                    <div class="whatsapp-attachment-icon whatsapp-attachment-audio">
                                        <i class="fas fa-microphone"></i>
                                    </div>
                                    <span class="whatsapp-attachment-label">Audio</span>
                                </button>
                            </div>
                        </div>

                        <div class="whatsapp-input-container">
                            <div class="whatsapp-input-wrapper">
                                <button type="button"
                                        class="whatsapp-attachment-btn"
                                        @click="toggleAttachmentMenu()"
                                        x-ref="attachmentBtn"
                                        :class="{ 'whatsapp-attachment-btn-active': isAttachmentMenuOpen }">
                                    <i class="fas fa-plus"></i>
                                </button>
                                
                                <div class="whatsapp-input-field-wrapper">
                                    <textarea class="whatsapp-message-input"
                                              x-ref="messageInput"
                                              wire:model="message"
                                              @keydown="handleKeydown($event)"
                                              @input="messageLength = $event.target.value.length"
                                              :maxlength="maxLength"
                                              placeholder="Type a message"
                                              rows="1"
                                              :disabled="$wire.sending"
                                              :class="{ 'whatsapp-input-error': messageLength > maxLength }"></textarea>
                                    
                                    <div class="whatsapp-input-controls">
                                        <div class="whatsapp-char-counter"
                                             :class="{
                                                'whatsapp-char-counter-warning': messageLength > maxLength * 0.8 && messageLength <= maxLength,
                                                'whatsapp-char-counter-error': messageLength > maxLength
                                             }">
                                            <span x-text="messageLength"></span>/<span x-text="maxLength"></span>
                                        </div>
                                        
                                        <button type="button" 
                                                class="whatsapp-microphone-btn"
                                                x-show="!$wire.message.trim()">
                                            <i class="fas fa-microphone"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <button type="button" 
                                        class="whatsapp-send-btn"
                                        @click="$wire.sendMessage()"
                                        wire:loading.attr="disabled"
                                        :disabled="!$wire.message.trim() || $wire.sending || messageLength > maxLength">
                                    <span wire:loading.remove wire:target="sendMessage">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <span wire:loading wire:target="sendMessage">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                            
                            <div class="whatsapp-status-bar">
                                <div class="whatsapp-status-left">
                                    <span class="whatsapp-status-item">
                                        <i class="fas fa-lock"></i>
                                        <span class="whatsapp-status-text">End-to-end encrypted</span>
                                    </span>
                                    <span class="whatsapp-status-item">
                                        <i class="fas fa-history"></i>
                                        <span class="whatsapp-status-text">History saved for 30 days</span>
                                    </span>
                                </div>
                                <div class="whatsapp-status-right">
                                    <button type="button" class="whatsapp-status-btn" @click="$wire.closeModal()">
                                        <i class="fas fa-times"></i>
                                        <span>Close</span>
                                    </button>
                                    <span class="whatsapp-status-divider">•</span>
                                    <button type="button" class="whatsapp-status-btn whatsapp-clear-btn" @click="$wire.clearChat()">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Clear</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', function () {
    // Auto-focus when modal opens
    Livewire.on('open-chat-modal', () => {
        setTimeout(() => {
            const input = document.querySelector('[x-ref="messageInput"]');
            if (input) {
                input.focus();
                input.style.height = 'auto';
                input.style.height = (input.scrollHeight) + 'px';
                
                setTimeout(() => {
                    const container = document.querySelector('[x-ref="messagesContainer"]');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 100);
            }
        }, 300);
    });

    // Auto-resize textarea
    document.addEventListener('input', function(e) {
        if (e.target.hasAttribute('wire:model') && e.target.getAttribute('wire:model') === 'message') {
            const textarea = e.target;
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        }
    });

    // Auto-refresh messages
    let refreshInterval;
    
    Livewire.on('modal-opened', () => {
        refreshInterval = setInterval(() => {
            if (!document.hidden) {
                Livewire.dispatch('loadMessages');
            }
        }, 5000);
    });
    
    Livewire.on('modal-closed', () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        const chatOpen = document.querySelector('[x-data] [x-show="isOpen"]');
        
        if (!chatOpen) return;
        
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const input = document.querySelector('[x-ref="messageInput"]');
            if (input) input.focus();
        }
        
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            const button = document.querySelector('[x-ref="attachmentBtn"]');
            if (button) button.click();
        }
    });

    // Typing detection
    let typingTimeout;
    document.addEventListener('input', function(e) {
        if (e.target.hasAttribute('wire:model') && e.target.getAttribute('wire:model') === 'message') {
            clearTimeout(typingTimeout);
            Livewire.dispatch('typing-start');
            
            typingTimeout = setTimeout(() => {
                Livewire.dispatch('typing-stop');
            }, 1000);
        }
    });

    // Smooth scroll for new messages
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList') {
                const container = document.querySelector('[x-ref="messagesContainer"]');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        });
    });

    Livewire.on('modal-opened', () => {
        const messagesContainer = document.querySelector('[x-ref="messagesContainer"]');
        if (messagesContainer) {
            observer.observe(messagesContainer, { childList: true, subtree: true });
        }
    });

    Livewire.on('modal-closed', () => {
        observer.disconnect();
    });
});

window.addEventListener('resize', function() {
    const textarea = document.querySelector('[x-ref="messageInput"]');
    if (textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }
});
</script>