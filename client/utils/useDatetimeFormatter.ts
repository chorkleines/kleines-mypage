export const useDatetimeFormatter = () => {
  const datetimeFormatter = new Intl.DateTimeFormat("ja-JP", {
    dateStyle: "medium",
    timeStyle: "medium",
  });
  const dateFormatter = new Intl.DateTimeFormat("ja-JP", {
    dateStyle: "medium",
  });

  function datetimeToString(datetime) {
    if (datetime === undefined || datetime === null || datetime === "")
      return null;
    return datetimeFormatter.format(new Date(datetime));
  }

  function dateToString(date) {
    if (date === undefined || date === null || date === "") return null;
    return dateFormatter.format(new Date(date));
  }
  return {
    datetimeToString,
    dateToString,
  };
};
