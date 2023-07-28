type IndividualAccounting = {
  accountingPayment?: AccountingPayment;
  datetime: string;
  individualAccountingList?: IndividualAccountingList;
  price: number;
  datetimeFormatted?: string;
  priceFormatted?: string;
  name?: string;
};

type IndividualAccountingList = {
  datetime: string;
  id: number;
  name: string;
};

type AccountingPayment = {
  accountingRecord: AccountingRecord;
  id: number;
  method: string;
  price: number;
};

type AccountingRecord = {
  accountingList: AccountingList;
  datetime: string;
  id: number;
  isPaid: boolean;
  price: number;
};

type AccountingList = {
  id: number;
  admin: string;
  deadline: string;
  name: string;
};

const createIndividualAccounting = (a) => {
  let individualAccounting: IndividualAccounting | null = null;
  if (a === null) {
    individualAccounting = null;
  } else {
    individualAccounting = {
      accountingPayment: createAccountingPayment(a.accounting_payment),
      datetime: a.datetime,
      individualAccountingList: createIndividualAccountingList(
        a.individual_accounting_list,
      ),
      price: a.price,
    };
  }
  return individualAccounting;
};

const createAccountingPayment = (a) => {
  let accountingPayment: AccountingPayment | null = null;
  if (a === null) {
    accountingPayment = null;
  } else {
    accountingPayment = {
      accountingRecord: createAccountingRecord(a.accounting_record),
      id: a.id,
      method: a.method,
      price: a.price,
    };
  }
  return accountingPayment;
};

const createAccountingRecord = (a) => {
  let accountingRecord: AccountingRecord | null = null;
  if (a === null) {
    accountingRecord = null;
  } else {
    accountingRecord = {
      accountingList: createAccountingList(a.accounting_list),
      datetime: a.datetime,
      id: a.id,
      isPaid: a.is_paid,
      price: a.price,
    };
  }
  return accountingRecord;
};

const createAccountingList = (a) => {
  let accountingList: AccountingList | null = null;
  if (a === null) {
    accountingList = null;
  } else {
    accountingList = {
      id: a.id,
      admin: a.admin,
      deadline: a.deadline,
      name: a.name,
    };
  }
  return accountingList;
};

const createIndividualAccountingList = (a) => {
  let individualAccountingList: IndividualAccountingList | null = null;
  if (a === null) {
    individualAccountingList = null;
  } else {
    individualAccountingList = {
      datetime: a.datetime,
      id: a.id,
      name: a.name,
    };
  }
  return individualAccountingList;
};

export const useIndividualAccountings = () => {
  const individualAccountings = ref([]);

  async function getIndividualAccountings() {
    const { data } = await useApiFetch("/api/individual_accountings", {
      method: "GET",
    });
    data.value.forEach((a) => {
      const individualAccounting = createIndividualAccounting(a);
      individualAccountings.value.push(individualAccounting);
    });

    individualAccountings.value.forEach(
      (individualAccounting: IndividualAccounting) => {
        individualAccounting.priceFormatted = new Intl.NumberFormat("ja-JP", {
          style: "currency",
          currency: "JPY",
        }).format(individualAccounting.price);
        individualAccounting.datetimeFormatted = new Date(
          individualAccounting.datetime,
        ).toLocaleString();
        individualAccounting.name =
          individualAccounting.individualAccountingList !== null
            ? individualAccounting.individualAccountingList?.name
            : individualAccounting.accountingPayment?.accountingRecord
                .accountingList.name;
      },
    );
  }

  return {
    individualAccountings,
    getIndividualAccountings,
  };
};
