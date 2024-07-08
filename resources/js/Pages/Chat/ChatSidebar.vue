<template>
  <div class="chat-detail relative w-full">
    <ChatHeader :conversation="conversation" />

    <!-- <div
      class="loading-box"
      :class="{
        'loading-top': isLoadingTop,
        'loading-bottom': isLoadingBottom,
      }"
    >
      <div v-loading="isLoadingTop || isLoadingBottom"></div>
    </div> -->
    <div ref="messagesBoxRef" class="messages-box">
      <div ref="messagesListRef" class="w-full"></div>
    </div>

    <ChatFooter :conversation="conversation" />
  </div>
</template>

<script setup>
import ChatHeader from "@/Pages/Chat/ChatHeader.vue";
import ChatFooter from "@/Pages/Chat/ChatFooter.vue";
const props = defineProps({
  conversation: { type: Object, default: () => {} },
});

window.Echo.private(`private_conversation.${props?.conversation?.id}`).listen('SendMessageEvent', async (event) => {
  console.log(123)
    // const { message } = event
    // if (messagesStore.checkMessageExist(message) || !messagesStore.checkCanShowMessage(message, currentUser.value.id)) {
    //     return
    // }

    // messagesStore.addNewerMessage([message])
    // await nextTick()
    // scrollToMessage(message, 'end')
})

</script>