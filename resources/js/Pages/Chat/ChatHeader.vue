<template>
  <div class="msg-head relative border-b pb-1 border-[#C1C1C1] p-2 !w-full">
    <div class="flex">
      <div class="">
        <div class="flex items-center !py-[10px]">
          <div class="ml-1 mr-2 relative flex items-center">
            <el-image
              class="w-9 h-9 rounded-full"
              :src="getConversationAvatar()"
            />
          </div>
          <div>
            <h3 class="font-bold text-[24px] leading-[31px]">
              {{ getConversationName() }}
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
  conversation: { type: Object, default: () => {} },
});

// get conversation name
const currentUser = computed(() => {
  return usePage().props.auth.user;
});
const getReceiver = () => {
  if (!props?.conversation?.users?.length) return;
  const receiver = props?.conversation.users.find(
    (user) => user.id != currentUser.value.id
  );

  return receiver ?? {};
};
const getConversationName = (conversation) => {
  return getReceiver(conversation)?.name;
};
const getConversationAvatar = (conversation) => {
  return getReceiver(conversation)?.profile_photo_url;
};
</script>
