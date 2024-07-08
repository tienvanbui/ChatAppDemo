<template>
  <div class="chat-sidebar">
    <div class="list-chat-box">
      <div
        ref="conversationsBoxRef"
        scroll-region
        class="list-item"
        @scroll="onLoadMore"
      >
        <div ref="conversationsRef" class="list-item-inner">
          <div v-if="conversations">
            <div
              v-for="conversation in conversations"
              :key="conversation.id"
              class="conversation-item"
              :class="{
                'is-active': (isActive = conversation.id == conversationId),
              }"
            >
              <Link
                :href="route('chats.index', conversation.id)"
                class="w-full relative flex"
                :title="getReceiverName(conversation)"
                preserve-state
                preserve-scroll
              >
                <div class="avatar mr-3 relative">
                  <el-image
                    class="avatar rounded-full"
                    :src="getReceiverAvatar(conversation)"
                  />
                </div>
                <div class="grow overflow-hidden">
                  <div class="flex justify-between items-center mt-1">
                    <span
                      class="conversation-title text-base truncate font-bold"
                    >
                      {{ getReceiverName(conversation) }}
                    </span>
                    <div
                      class="time min-w-[63px] text-[#8A8A8A] text-xs text-right"
                    >
                      {{
                        formatTimeConversation(
                          conversation?.message?.created_at ??
                            conversation.created_at
                        )
                      }}
                    </div>
                  </div>
                  <div class="flex items-center mt-1.5">
                    <div
                      class="conversation-content text-sm truncate"
                      :class="{
                        'font-bold text-[#FFA41C]':
                          conversation.unread_messages !== 0,
                      }"
                    >
                      {{ getMessageContent(conversation.message) }}
                    </div>
                    <!-- v-if="unread messages" -->
                    <div class="news ml-auto w-[50px] flex justify-end">
                      <div
                        v-if="conversation.unread_messages"
                        class="bg-[#34C759] rounded-[22px] w-max"
                      >
                        <span
                          class="text-white text-xs font-bold py-[3px] px-[8px] flex items-center"
                        >
                          {{ conversation.unread_messages }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </Link>
            </div>
          </div>

          <div
            v-show="isLoadMore"
            v-loading="isLoadMore"
            class="conversation-item h-10"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick, onBeforeUnmount } from "vue";
import { useConversationsStore } from "@/Stores/conversations.js";
import { usePage } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";
import timeFormatter from "@/Composables/datetime.js";
import commonsable from "@/Composables/common.js";

const { formatTimeConversation } = timeFormatter();

const { currentUser , getReceiver , getReceiverName } = commonsable();

const conversationStore = useConversationsStore();

const fetchConversations = async (page = 1) => {
  await conversationStore.getConversations({ page: page });
};

fetchConversations();

const conversations = computed(() => {
  return conversationStore.conversations;
});
const conversationsPagination = computed(() => {
  return conversationStore.conversationsPagination;
});
const currentPage = computed(() => {
  return conversationsPagination.value?.current_page ?? 1;
});
const lastPage = computed(() => {
  return conversationsPagination.value?.last_page ?? 1;
});
const isHasNextPage = computed(() => {
  return currentPage.value < lastPage.value;
});

// check list conversations to fit height of sidebar
const conversationsBoxRef = ref(null);
const conversationsRef = ref(null);
watch([conversationsBoxRef, conversations], async () => {
  await nextTick();

  if (
    isHasNextPage.value &&
    conversationsBoxRef.value.clientHeight > conversationsRef.value.clientHeight
  ) {
    await fetchConversations(currentPage.value + 1);
  }
});

// handle load more conversations when scroll
const isLoadMore = ref(false);
const onLoadMore = async () => {
  const element = conversationsBoxRef.value;
  const scrollHeight = element.scrollHeight;
  const clientHeight = element.clientHeight;

  const distanceToTop = element.scrollTop;
  const distanceToBottom = scrollHeight - (distanceToTop + clientHeight);

  if (distanceToBottom < 10 && !isLoadMore.value && isHasNextPage.value) {
    isLoadMore.value = true;
    await fetchConversations(currentPage.value + 1);
    isLoadMore.value = false;
  }
};
const getReceiverAvatar = (conversation) => {
  return getReceiver(conversation).avatar;
};
const conversationId = computed(() => conversationStore.conversationId);

const getMessageContent = (message) => message.content;
</script>