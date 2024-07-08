import dayjs from "dayjs";

export default () => {
  const formatDateTime = (value) => {
    return dayjs(value).format("YYYY/MM/DD HH:mm");
  };

  const formatDate = (value) => {
    return dayjs(value).format("YYYY/MM/DD");
  };

  const formatTime = (value) => {
    return dayjs(value).format("HH:mm");
  };

  const formatMonthDate = (value) => {
    return dayjs(value).format("MM/DD");
  };

  const formatTimeConversation = (value) => {
    const time1 = dayjs().format("YYYY/MM/DD");
    const time2 = dayjs(value).format("YYYY/MM/DD");
    const diffDays = dayjs(time1).diff(dayjs(time2), "day");

    if (diffDays === 0) {
      return formatTime(value);
    }

    return formatDate(value);
  };

  const formatTimeChat = (value) => {
    return dayjs(value).format("YYYY/MM/DD HH:mm");
  };

  return {
    formatDateTime,
    formatDate,
    formatTime,
    formatMonthDate,
    formatTimeConversation,
    formatTimeChat,
  };
};
