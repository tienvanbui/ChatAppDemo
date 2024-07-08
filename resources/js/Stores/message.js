import { defineStore } from "pinia";

export const useMessagesStore = defineStore("messages", {
  state: () => ({
    messages: null,
  }),
  actions: {
    async sendMessage(formData, conversation_id) {
      const conversationId = conversation_id;
      const isCanSendMessage = conversationId && formData.content;
      if (!isCanSendMessage) return;

      await axiosPlugin.post(
        route("api.messages.store", {
          conversation_id: conversationId,
        }),
        formData
      );
    },
  },
});
