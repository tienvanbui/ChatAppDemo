<template>
  <div>
    <AppLayout title="Chat">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
      </template>
      <div class="py-[10px] !h-full">
        <div class="!h-full">
          <div class="bg-white !h-full">
            <el-container class="!h-full">
              <keep-alive>
                <div class="w-full flex">
                  <ManageChatBox class="!w-1/4"/>
                  <ChatSidebar :conversation="conversation" class="!w-3/4"/>
                </div>
              </keep-alive>
            </el-container>
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import ChatSidebar from "./ChatSidebar.vue";
import ManageChatBox from "./ManageChatBox.vue";

const props = defineProps({
  conversation: { type: Object, default: () => {} },
  lastMessageSeen: { type: Object, default: () => {} },
  conversationIdParam: { type: String, default: null },
});
</script>
