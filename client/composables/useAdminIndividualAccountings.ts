type IndividualAccountingList = {
  datetime: string;
  id: number;
  name: string;
  individualAccountingRecords?: IndividualAccountingRecord[];
  datetimeFormatted?: string;
  route?: string;
};

type IndividualAccountingRecord = {
  datetime: string;
  price: number;
  user: User;
  datetimeFormatted?: string;
  priceFormatted?: string;
  route?: string;
};

type User = {
  id: number;
  status: string;
  email?: string;
  profile: Profile;
  statusFormatted?: string;
};

type Profile = {
  grade: number;
  part: string;
  firstName: string;
  lastName: string;
  nameKana?: string;
  fullName?: string;
  displayName?: string;
  partFormatted?: string;
  birthday?: string;
  birthdayFormatted?: string;
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

const createIndividualAccountingRecord = (data) => {
  let individualAccountingRecord: IndividualAccountingRecord | null = null;
  if (data === null) {
    individualAccountingRecord = null;
  } else {
    individualAccountingRecord = {
      datetime: data.datetime,
      price: data.price,
      user: createUser(data.user)!,
    };
  }
  return individualAccountingRecord;
};

const createUser = (u) => {
  let user: User | null = null;
  if (u == null) {
    user = null;
  } else {
    user = {
      id: u.id,
      status: u.status,
      profile: {
        grade: u.profile.grade,
        part: u.profile.part,
        firstName: u.profile.first_name,
        lastName: u.profile.last_name,
        nameKana: u.profile.name_kana,
      },
    };
  }
  return user;
};

export const useAdminIndividualAccountings = () => {
  const individualAccountingLists = ref([]);
  const individualAccountingList = ref({
    id: 0,
    name: "",
    datetime: "",
    individualAccountingRecords: [],
  });

  async function getIndividualAccountingLists() {
    const { data } = await useApiFetch(
      "/api/admin/individual-accountings/lists",
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
      individualAccountingList.route = `/admin/individual_accountings/lists/${individualAccountingList.id}`;
    });
  }

  async function getIndividualAccountingList(id: number) {
    const { data } = await useApiFetch(
      `/api/admin/individual-accountings/lists/${id}`,
      {
        method: "GET",
      },
    );
    individualAccountingList.value = createIndividualAccountingList(data.value);
    console.log(individualAccountingList);
    individualAccountingList.value.individualAccountingRecords = [];
    data.value.individual_accounting_records.forEach((d) => {
      const individualAccountingRecord = createIndividualAccountingRecord(d);
      individualAccountingRecord.user.profile.fullName = `${individualAccountingRecord.user.profile.lastName} ${individualAccountingRecord.user.profile.firstName}`;
      individualAccountingRecord.user.profile.displayName = `${individualAccountingRecord.user.profile.grade}${individualAccountingRecord.user.profile.part} ${individualAccountingRecord.user.profile.lastName}${individualAccountingRecord.user.profile.firstName}`;
      switch (individualAccountingRecord.user.status) {
        case "PRESENT":
          individualAccountingRecord.user.statusFormatted = "在団";
          break;
        case "ABSENT":
          individualAccountingRecord.user.statusFormatted = "休団";
          break;
        case "RESIGNED":
          individualAccountingRecord.user.statusFormatted = "退団";
          break;
        default:
          individualAccountingRecord.user.statusFormatted = "";
          break;
      }
      switch (individualAccountingRecord.user.profile.part) {
        case "S":
          individualAccountingRecord.user.profile.partFormatted = "Soprano";
          break;
        case "A":
          individualAccountingRecord.user.profile.partFormatted = "Alto";
          break;
        case "T":
          individualAccountingRecord.user.profile.partFormatted = "Tenor";
          break;
        case "B":
          individualAccountingRecord.user.profile.partFormatted = "Bass";
          break;
        default:
          individualAccountingRecord.user.profile.partFormatted = "";
          break;
      }
      individualAccountingRecord.priceFormatted = new Intl.NumberFormat(
        "ja-JP",
        {
          style: "currency",
          currency: "JPY",
        },
      ).format(individualAccountingRecord.price);
      const { datetimeToString } = useDatetimeFormatter();
      individualAccountingRecord.datetimeFormatted = datetimeToString(
        individualAccountingRecord.datetime,
      );
      individualAccountingRecord.route = `/admin/individual_accountings/users/${individualAccountingRecord.user.id}`;
      individualAccountingList.value.individualAccountingRecords.push(
        individualAccountingRecord,
      );
    });
  }

  return {
    individualAccountingLists,
    individualAccountingList,
    getIndividualAccountingLists,
    getIndividualAccountingList,
  };
};
