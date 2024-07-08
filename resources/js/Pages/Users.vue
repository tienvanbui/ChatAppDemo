<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { Search, Plus, ChatRound } from "@element-plus/icons-vue";
import { router } from "@inertiajs/vue3";

const users = ref([]);

const fetchUsers = async () => {
  const { data, status } = await axiosPlugin.get(route("api.users.index"));

  if (status == 200) {
    users.value = data?.data;
  }
};

const filter = ref({
  keyword: "",
});

fetchUsers();

const chatWith = async (id) => {
  axiosPlugin.get(route("api.conversation.person", { id: id })).then((res) => {
    if (res?.status == 200) {
      router.get(route("chats.index", { id: res?.data?.data?.id }));
    }
  });
};
</script>

<template>
  <AppLayout title="Users">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="w-full !px-[15px] !py-[15px]">
            <el-input
              v-model="filter.keyword"
              class="w-full"
              size="large"
              placeholder="Search by keyword"
              :suffix-icon="Search"
            />
          </div>
          <div class="w-full !px-[15px]">
            <div
              v-for="user in users"
              :key="user.id"
              class="h-[60px] flex items-center my-[10px] user-login_box flex justify-between"
            >
              <div class="flex">
                <img
                  :src="user?.profile_photo_url"
                  class="h-[50px] w-[50px] ml-[10px]"
                />
                <div class="ml-[20px]">
                  <p class="flex items-center !mb-[1px]">
                    <span class="user-login_status"></span
                    ><span class="ml-[5px]">Working</span>
                  </p>
                  <p class="text-base font-bold">{{ user?.name }}</p>
                </div>
              </div>
              <div class="flex flex-col mr-[5px]">
                <el-button
                  type="primary"
                  class="text-sm text-white !w-[100px] !h-[25px]"
                  ><el-icon><Plus /></el-icon><span>Add friend</span></el-button
                >
                <el-button
                  type="success"
                  class="text-sm text-white !w-[100px] !h-[25px] !ml-[0px] !mt-[2px]"
                  @click="chatWith(user?.id)"
                  ><el-icon><ChatRound /></el-icon><span>Chat</span></el-button
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<style scoped lang="scss">
.user-login_box {
  box-shadow: 0px 0px 2px 0px rgba($color: #5a6971, $alpha: 0.6) inset;
  .user-login_status {
    width: 10px;
    height: 10px;
    background-color: #67c23a;
    border-radius: 99px;
  }
}
</style>
