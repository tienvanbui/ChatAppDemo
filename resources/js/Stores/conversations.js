import { defineStore } from "pinia";

export const useConversationsStore = defineStore("conversations", {
  state: () => ({
    conversations: [],
    conversationsPagination: {},
    conversationId: null,
  }),
  actions: {
    resetData() {
      this.conversations = [];
      this.conversationsPagination = {};
    },

    async getConversations(filter = {}) {
      filter.limit = 10;
      await axiosPlugin
        .get(route("api.conversations.index"), { params: { ...filter } })
        .then(({ data }) => {
          if (filter.page === 1) {
            this.conversations = data.data;
          } else {
            this.conversations = [...this.conversations, ...data.data];
          }
          this.conversationsPagination = data.meta;
        });
    },
    onPinConversation(conversation_id) {
      return axiosPlugin.put(
        route("api.conversations.pin", { id: conversation_id })
      );
    },
    onUnpinConversation(conversation_id) {
      return axiosPlugin.put(
        route("api.conversations.unpin", { id: conversation_id })
      );
    },
  },
});
