import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
export default () => {
  const currentUser = computed(() => {
    return usePage().props?.auth?.user;
  });
  
  const getReceiver = (conversation) => {
    if (!conversation?.users.length) return;
    const receiver = conversation?.users.find(
      (user) => user.id != currentUser.value.id
    );

    return receiver ?? {};
  };
  
  const getReceiverName = (conversation) => {
    return getReceiver(conversation).name;
  };


  return {
    currentUser,
    getReceiver,
    getReceiverName,
  };
};
