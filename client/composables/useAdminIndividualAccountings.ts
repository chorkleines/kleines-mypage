type IndividualAccountingList = {
  datetime: string;
  id: number;
  name: string;
  datetimeFormatted?: string;
  route?: string;
};

const createIndividualAccountingList = (data) => {
  let individualAccountingList: IndividualAccountingList | null = null;
  if (data === null) {
    individualAccountingList = null;
  } else {
    individualAccountingList = {
      datetime: data.datetime,
      id: data.id,
      name: data.name,
    };
  }
  return individualAccountingList;
};

export const useAdminIndividualAccountings = () => {
  const individualAccountingLists = ref([]);

  async function getIndividualAccountingLists() {
    const { data } = await useApiFetch(
      "/api/admin/individual-accountings/list",
      {
        method: "GET",
      },
    );
    data.value.forEach((d) => {
      const individualAccountingList = createIndividualAccountingList(d);
      individualAccountingLists.value.push(individualAccountingList);
    });
    const { datetimeToString } = useDatetimeFormatter();
    individualAccountingLists.value.forEach((individualAccountingList) => {
      individualAccountingList.datetimeFormatted = datetimeToString(
        individualAccountingList.datetime,
      );
      individualAccountingList.route = `/admin/individual-accountings/lists/${individualAccountingList.id}`;
    });
  }

  return {
    individualAccountingLists,
    getIndividualAccountingLists,
  };
};
