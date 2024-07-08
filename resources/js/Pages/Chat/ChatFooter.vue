<script setup>
import { ref, computed } from "vue";
import { useMessagesStore } from "@/Stores/message.js";
import ImageIcon from "@/Assets/svg/ImageIcon.svg";
// import UploadFile from './UploadFile.vue'
import { ElMessage } from "element-plus";
import { useShareDataStore } from "@/Stores/shareData.js";

const props = defineProps({
  conversation: { type: Object, default: () => {} },
});

const shareDataStore = useShareDataStore();

const currentUser = computed(() => {
  return shareDataStore.currentUser;
});

const messagesStore = useMessagesStore();

// const receiver = computed(() => {
//     return messagesStore.conversation?.users?.find((user) => user.id !== currentUser.value.id)
// })

const formData = ref({
  content: "",
});
const resetFormData = () => {
  formData.value = {
    content: "",
    conversation_id: props?.conversation?.id,
  };
};
const isLoading = ref(false);

const emit = defineEmits(["newMessage", "on-error"]);

const sendMessage = () => {
  if (isLoading.value) return;
  isLoading.value = true;
  messagesStore
    .sendMessage(formData.value, props?.conversation?.id)
    .then((response) => {
      console.log(response);
      resetFormData();
      const message = response?.data?.data;
      if (!message) {
        return emit("on-error");
      }
      // if (messagesStore.checkMessageExist(message)) return

      // messagesStore.addNewerMessage([message])
      // emit('newMessage', message)
    })
    .catch((error) => {
      const message = error?.response?.data?.message;

      if (message) {
        ElMessage.error(message);
      }
      emit("on-error");
    })
    .finally(() => {
      isLoading.value = false;
    });
};
</script>

<template>
  <div class="chat-footer">
    <div class="mt-2">
      <!-- <div class="quote-block"></div> -->
      <div class="input-box flex items-center">
        <!-- <div class="cursor-pointer mr-2" @click="onChooseFile">
                    <ImageIcon />
                </div> -->
        <div class="input-text-wrapper">
          <el-input
            v-model="formData.content"
            type="textarea"
            class="text-black input-text"
            placeholder="Enter the message"
            :autosize="{ minRows: 2, maxRows: 3 }"
            resize="none"
            @keypress.enter="sendMessage"
          />

          <div class="btn-send" @click="sendMessage">
            <i class="ri-send-plane-fill"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="attachment-list">
            <div class="">
                <UploadFile
                    input-upload-id="inputUploadId"
                    :attachments="formData.attachments"
                    @on-files-change="onFilesChange"
                />
            </div>
        </div> -->
  </div>
</template>
