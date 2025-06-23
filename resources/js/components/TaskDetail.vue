<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">
            Уведомления о новых комментариях
        </h2>

        <transition name="fade">
            <div v-if="comment" class="bg-white rounded-xl shadow-lg p-6 max-w-md mx-auto border border-gray-100">
                <h3 class="text-lg font-semibold text-blue-600 mb-2">Новый комментарий получен 🎉</h3>
                <p><span class="font-medium text-gray-600">Задача:</span> {{ comment?.task?.title }}</p>
                <p><span class="font-medium text-gray-600">Сообщение:</span> {{ comment.message }}</p>
                <p><span class="font-medium text-gray-600">Дата:</span> {{ new Date(comment.created_at).toLocaleString() }}</p>
            </div>
            <div v-else class="text-center mt-8 text-gray-500 italic">
                Нет комментариев пока...
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

const comment = ref(null)

onMounted(() => {
    window.Pusher = Pusher
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
    })

    window.Echo.channel('task-comments').listen('.CommentCreated', (e) => {
        comment.value = e
    })
})

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leaveChannel('task-comments')
    }
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
